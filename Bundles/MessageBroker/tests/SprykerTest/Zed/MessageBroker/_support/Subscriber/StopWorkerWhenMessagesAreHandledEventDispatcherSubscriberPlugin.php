<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\_support\Subscriber;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\Messenger\Event\WorkerStartedEvent;
use Symfony\Component\Messenger\Worker;

class StopWorkerWhenMessagesAreHandledEventDispatcherSubscriberPlugin extends AbstractPlugin implements EventSubscriberInterface
{
    protected ?Worker $worker = null;

    /**
     * @param \Symfony\Component\Messenger\Event\WorkerStartedEvent $event
     *
     * @return void
     */
    public function onWorkerStarted(WorkerStartedEvent $event): void
    {
        $this->worker = $event->getWorker();
    }

    /**
     * @param \Symfony\Component\Messenger\Event\WorkerMessageHandledEvent $event
     *
     * @return void
     */
    public function onWorkerMessageHandled(WorkerMessageHandledEvent $event): void
    {
        $this->worker->stop();
    }

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkerStartedEvent::class => 'onWorkerStarted',
            WorkerMessageHandledEvent::class => 'onWorkerMessageHandled',
        ];
    }
}
