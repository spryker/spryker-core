<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication\Plugin;

use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleLogPlugin implements EventSubscriberInterface
{
    use LoggerTrait;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $context = [
            'arguments' => $event->getInput()->getArguments(),
            'options' => $event->getInput()->getOptions(),
        ];

        $this->getLogger()->info(sprintf('CLI command "%s" started', $event->getCommand()->getName()), $context);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        $this->getLogger()->info(sprintf('CLI command "%s" terminated', $event->getCommand()->getName()));
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleErrorEvent $event
     *
     * @return void
     */
    public function onConsoleError(ConsoleErrorEvent $event)
    {
        $exception = $event->getError();

        $this->getLogger()->error(sprintf(
            'CLI command "%s" exception, message "%s"',
            $this->getConsoleErrorCommandName($event),
            $exception->getMessage()
        ), ['exception' => $exception]);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleEvent $consoleEvent
     *
     * @return string
     */
    protected function getConsoleErrorCommandName(ConsoleEvent $consoleEvent): string
    {
        if ($consoleEvent->getCommand()) {
            return $consoleEvent->getCommand()->getName();
        }

        return $consoleEvent->getInput()->getFirstArgument() ?: '';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => ['onConsoleCommand'],
            ConsoleEvents::TERMINATE => ['onConsoleTerminate'],
            ConsoleEvents::ERROR => ['onConsoleError'],
        ];
    }
}
