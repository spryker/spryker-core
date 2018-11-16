<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication\Plugin;

use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleLogPlugin implements EventSubscriberInterface
{
    use LoggerTrait;

    /**
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
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleExceptionEvent $event
     *
     * @return void
     */
    public function onConsoleException(ConsoleExceptionEvent $event)
    {
        $exception = $event->getException();

        $this->getLogger()->error(sprintf(
            'CLI command "%s" exception, message "%s"',
            $event->getCommand()->getName(),
            $exception->getMessage()
        ), ['exception' => $exception]);
    }

    /**
     * @api
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => ['onConsoleCommand'],
            ConsoleEvents::TERMINATE => ['onConsoleTerminate'],
            ConsoleEvents::EXCEPTION => ['onConsoleException'],
        ];
    }
}
