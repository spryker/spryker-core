<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Dispatcher;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;

class EventListenerContext implements EventListenerContextInterface
{
    /**
     * @var \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface
     */
    protected $eventHandler;

    /**
     * @var bool
     */
    protected $isHandledInQueue;

    /**
     * @var string|null
     */
    protected $queuePoolName;

    /**
     * @var string|null
     */
    protected $eventQueueName;

    /**
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param bool $isHandledInQueue
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     */
    public function __construct(EventBaseHandlerInterface $eventHandler, $isHandledInQueue, $queuePoolName = null, $eventQueueName = null)
    {
        $this->eventHandler = $eventHandler;
        $this->isHandledInQueue = $isHandledInQueue;
        $this->queuePoolName = $queuePoolName;
        $this->eventQueueName = $eventQueueName;
    }

    /**
     * @return bool
     */
    public function isHandledInQueue()
    {
        return $this->isHandledInQueue;
    }

    /**
     * @return string|null
     */
    public function getQueuePoolName()
    {
        return $this->queuePoolName;
    }

    /**
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return $this->eventQueueName;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $transfer, $eventName)
    {
        if ($this->eventHandler instanceof EventHandlerInterface) {
            $this->eventHandler->handle($transfer, $eventName);
        }
    }

    /**
     * @param array $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName)
    {
        if ($this->eventHandler instanceof EventBulkHandlerInterface) {
            $this->eventHandler->handleBulk($transfers, $eventName);
        }
    }

    /**
     * @return string
     */
    public function getListenerName()
    {
        return get_class($this->eventHandler);
    }
}
