<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring\Plugin\Console;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Monitoring\MonitoringFactory;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @method \Spryker\Yves\Monitoring\MonitoringFactory getFactory()
 * @method \Spryker\Yves\Monitoring\MonitoringConfig getConfig()
 */
class MonitoringConsolePlugin extends AbstractPlugin implements EventSubscriberInterface
{
    protected const TRANSACTION_NAME_PREFIX = 'vendor/bin/console ';

    /**
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $factory = $this->getFactory();
        $transactionName = $this->getTransactionName($event);
        $hostName = $factory->getUtilNetworkService()->getHostName();
        $monitoring = $factory->getMonitoringService();

        $monitoring->markAsConsoleCommand();
        $monitoring->setTransactionName($transactionName);
        $monitoring->addCustomParameter('host', $hostName);

        $this->addArgumentsAsCustomParameter($event, $factory);
        $this->addOptionsAsCustomParameter($event, $factory);
    }

    /**
     * {@inheritDoc}
     *
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
     * @return string
     */
    protected function getTransactionName(ConsoleTerminateEvent $event): string
    {
        return static::TRANSACTION_NAME_PREFIX . $event->getCommand()->getName();
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     * @param \Spryker\Yves\Monitoring\MonitoringFactory $factory
     *
     * @return void
     */
    protected function addArgumentsAsCustomParameter(ConsoleTerminateEvent $event, MonitoringFactory $factory): void
    {
        $this->addCustomParameter($event->getInput()->getArguments(), $factory);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     * @param \Spryker\Yves\Monitoring\MonitoringFactory $factory
     *
     * @return void
     */
    protected function addOptionsAsCustomParameter(ConsoleTerminateEvent $event, MonitoringFactory $factory): void
    {
        $this->addCustomParameter($event->getInput()->getOptions(), $factory);
    }

    /**
     * @param array $customParameter
     * @param \Spryker\Yves\Monitoring\MonitoringFactory $factory
     *
     * @return void
     */
    protected function addCustomParameter(array $customParameter, MonitoringFactory $factory): void
    {
        $monitoring = $factory->getMonitoringService();

        foreach ($customParameter as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $monitoring->addCustomParameter($key, $value);
        }
    }
}
