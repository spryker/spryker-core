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
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param bool $isHandledInQueue
     * @param string|null $queuePoolName
     */
    public function __construct(EventBaseHandlerInterface $eventHandler, $isHandledInQueue, $queuePoolName = null)
    {
        $this->eventHandler = $eventHandler;
        $this->isHandledInQueue = $isHandledInQueue;
        $this->queuePoolName = $queuePoolName;
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $eventTransfer, $eventName)
    {
        if ($this->eventHandler instanceof EventHandlerInterface) {
            $this->eventHandler->handle($eventTransfer, $eventName);
        }
    }

    /**
     * @param array $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        if ($this->eventHandler instanceof EventBulkHandlerInterface) {
            $this->eventHandler->handleBulk($eventTransfers, $eventName);
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
