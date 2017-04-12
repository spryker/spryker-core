<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Dispatcher;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface;

class EventListenerContext implements EventListenerContextInterface
{

    /**
     * @var \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface
     */
    protected $eventListener;

    /**
     * @var bool
     */
    protected $isHandledInQueue;

    /**
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface $eventListener
     * @param bool $isHandledInQueue
     */
    public function __construct(EventListenerInterface $eventListener, $isHandledInQueue)
    {
        $this->eventListener = $eventListener;
        $this->isHandledInQueue = $isHandledInQueue;
    }

    /**
     * @return bool
     */
    public function isHandledInQueue()
    {
        return $this->isHandledInQueue;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function handle(TransferInterface $eventTransfer)
    {
        $this->eventListener->handle($eventTransfer);
    }

    /**
     * @return string
     */
    public function getListenerName()
    {
        return get_class($this->eventListener);
    }

}
