<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @method \Spryker\Zed\Monitoring\Communication\MonitoringCommunicationFactory getFactory()
 */
class MonitoringConsolePlugin extends AbstractPlugin implements EventSubscriberInterface
{
    const TRANSACTION_NAME_PREFIX = 'vendor/bin/console ';

    /**
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $transactionName = $this->getTransactionName($event);
        $hostName = $this->getFactory()->getUtilNetworkService()->getHostName();
        $monitoring = $this->getFactory()->getMonitoringService();

        $monitoring->markAsConsoleCommand();
        $monitoring->setTransactionName($transactionName);
        $monitoring->addCustomParameter('host', $hostName);

        $this->addArgumentsAsCustomParameter($event);
        $this->addOptionsAsCustomParameter($event);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return string
     */
    protected function getTransactionName(ConsoleTerminateEvent $event): string
    {
        return static::TRANSACTION_NAME_PREFIX . $event->getCommand()->getName();
    }

    /**
     * @api
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::TERMINATE => ['onConsoleTerminate'],
        ];
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    protected function addArgumentsAsCustomParameter(ConsoleTerminateEvent $event): void
    {
        $this->addCustomParameter($event->getInput()->getArguments());
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    protected function addOptionsAsCustomParameter(ConsoleTerminateEvent $event): void
    {
        $this->addCustomParameter($event->getInput()->getOptions());
    }

    /**
     * @param array $customParameter
     *
     * @return void
     */
    protected function addCustomParameter(array $customParameter): void
    {
        $monitoring = $this->getFactory()->getMonitoringService();

        foreach ($customParameter as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $monitoring->addCustomParameter($key, $value);
        }
    }
}
