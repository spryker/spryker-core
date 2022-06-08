<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business\EventHandler;

use Generated\Shared\Transfer\MonitoringTransactionEventTransfer;
use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class EventHandler implements EventHandlerInterface
{
    /**
     * @var string
     */
    protected const TRANSACTION_NAME_PREFIX = 'vendor/bin/console ';

    /**
     * @var string
     */
    protected const PARAMETER_HOST = 'host';

    /**
     * @var \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    protected $utilNetworkService;

    /**
     * @var array<\Spryker\Zed\Monitoring\Business\MonitoringTransactionNamingStrategy\MonitoringTransactionNamingStrategyInterface>
     */
    protected array $monitoringTransactionNamingStrategies = [];

    /**
     * @param \Spryker\Service\Monitoring\MonitoringServiceInterface $monitoringService
     * @param \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface $utilNetworkService
     * @param array<\Spryker\Zed\Monitoring\Business\MonitoringTransactionNamingStrategy\MonitoringTransactionNamingStrategyInterface> $monitoringTransactionNamingStrategies
     */
    public function __construct(
        MonitoringServiceInterface $monitoringService,
        MonitoringToUtilNetworkServiceInterface $utilNetworkService,
        array $monitoringTransactionNamingStrategies = []
    ) {
        $this->monitoringService = $monitoringService;
        $this->utilNetworkService = $utilNetworkService;
        $this->monitoringTransactionNamingStrategies = $monitoringTransactionNamingStrategies;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function handleConsoleTerminateEvent(ConsoleTerminateEvent $event): void
    {
        $this->monitoringService->markAsConsoleCommand();
        $this->monitoringService->setTransactionName($this->getTransactionName($event));
        $this->monitoringService->addCustomParameter(static::PARAMETER_HOST, $this->utilNetworkService->getHostName());

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
        $monitoringTransactionEventTransfer = $this->mapConsoleTerminateEventToMonitoringTransactionEventTransfer($event);
        foreach ($this->monitoringTransactionNamingStrategies as $monitoringTransactionNamingStrategy) {
            if ($monitoringTransactionNamingStrategy->isApplicable($monitoringTransactionEventTransfer)) {
                return $monitoringTransactionNamingStrategy->getMonitoringTransactionName($monitoringTransactionEventTransfer);
            }
        }

        return static::TRANSACTION_NAME_PREFIX . $event->getCommand()->getName();
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return \Generated\Shared\Transfer\MonitoringTransactionEventTransfer
     */
    protected function mapConsoleTerminateEventToMonitoringTransactionEventTransfer(
        ConsoleTerminateEvent $event
    ): MonitoringTransactionEventTransfer {
        $monitoringTransactionEventTransfer = new MonitoringTransactionEventTransfer();
        $monitoringTransactionEventTransfer->setCommandName($event->getCommand()->getName());
        $monitoringTransactionEventTransfer->setArguments($event->getInput()->getArguments());
        $monitoringTransactionEventTransfer->setTransactionNamePrefix(trim(static::TRANSACTION_NAME_PREFIX));

        return $monitoringTransactionEventTransfer;
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
        foreach ($customParameter as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $this->monitoringService->addCustomParameter($key, $value);
        }
    }
}
