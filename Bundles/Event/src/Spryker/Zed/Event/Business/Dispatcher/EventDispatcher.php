<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Dispatcher;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Business\Logger\EventLoggerInterface;
use Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected $eventCollection;

    /**
     * @var \Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface
     */
    protected $eventQueueProducer;

    /**
     * @var \Spryker\Zed\Event\Business\Logger\EventLoggerInterface
     */
    protected $eventLogger;

    /**
     * @var \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     * @param \Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface $eventQueueProducer
     * @param \Spryker\Zed\Event\Business\Logger\EventLoggerInterface $eventLogger
     * @param \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(
        EventCollectionInterface $eventCollection,
        EventQueueProducerInterface $eventQueueProducer,
        EventLoggerInterface $eventLogger,
        EventToUtilEncodingInterface $utilEncodingService
    ) {
        $this->eventCollection = $eventCollection;
        $this->eventQueueProducer = $eventQueueProducer;
        $this->eventLogger = $eventLogger;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function trigger($eventName, TransferInterface $transfer)
    {
        foreach ($this->extractEventListeners($eventName) as $eventListener) {
            if ($eventListener->isHandledInQueue()) {
                $this->eventQueueProducer->enqueueListener($eventName, $transfer, $eventListener->getListenerName(), $eventListener->getQueuePoolName());
            } elseif ($eventListener instanceof EventHandlerInterface) {
                $eventListener->handle($transfer, $eventName);
            }
            $this->logEventHandle($eventName, $transfer, $eventListener);
        }
    }

    /**
     * @param string $eventName
     *
     * @return \SplPriorityQueue|\Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface[]
     */
    protected function extractEventListeners($eventName)
    {
        if (!$this->eventCollection->has($eventName)) {
            return [];
        }

        return $this->eventCollection->get($eventName);
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface $eventListener
     *
     * @return void
     */
    protected function logEventHandle(
        $eventName,
        TransferInterface $transfer,
        EventListenerContextInterface $eventListener
    ) {
        $this->eventLogger->log(
            sprintf(
                $this->createHandleMessage($eventListener),
                $eventName,
                $eventListener->getListenerName(),
                get_class($transfer),
                $this->utilEncodingService->encodeJson($transfer->toArray())
            )
        );
    }

    /**
     * @param \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface $eventListener
     *
     * @return string
     */
    protected function createHandleMessage(EventListenerContextInterface $eventListener)
    {
        if ($eventListener->isHandledInQueue()) {
            return '[async] "%s" listener "%s", sent to the queue, event data: "%s" => "%s".';
        }

        return '[sync] "%s" handled by "%s", event data: "%s" => "%s".';
    }
}
