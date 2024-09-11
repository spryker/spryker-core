<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Communication\Plugin\Console;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @method \Spryker\Zed\Synchronization\SynchronizationConfig getConfig()
 * @method \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface getFacade()
 * @method \Spryker\Zed\Synchronization\Communication\SynchronizationCommunicationFactory getFactory()
 */
class DirectSynchronizationConsolePlugin extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     * - Syncs the buffered messages to storage/search when the console terminates.
     * - Marks the messages as failed if error occurs.
     * - Sends failed messages to queue as a fallback.
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::TERMINATE => ['onConsoleTerminate'],
        ];
    }

    /**
     * {@inheritDoc}
     * - Syncs the buffered messages to storage/search.
     * - Marks the messages as failed if error occurs.
     * - Sends failed messages to queue as a fallback.
     *
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $this->getFacade()->flushSynchronizationMessagesFromBuffer();
    }
}
