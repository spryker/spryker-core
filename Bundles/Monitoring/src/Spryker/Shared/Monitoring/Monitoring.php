<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Monitoring;

use Spryker\Shared\MonitoringExtension\MonitoringInterface;

class Monitoring implements MonitoringInterface
{
    /**
     * @var \Spryker\Shared\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface[]
     */
    protected $monitoringExtensionPlugins;

    /**
     * @param \Spryker\Shared\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface[] $monitoringExtensionPlugins
     */
    public function __construct(array $monitoringExtensionPlugins)
    {
        $this->monitoringExtensionPlugins = $monitoringExtensionPlugins;
    }

    /**
     * @param string $message
     * @param \Exception|\Throwable $exception
     *
     * @return void
     */
    public function setError(string $message, $exception): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setError($message, $exception);
        }
    }

    /**
     * @param string $application
     * @param string $store
     * @param string $environment
     *
     * @return void
     */
    public function setAppName(string $application, string $store, string $environment): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setAppName($application, $store, $environment);
        }
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setTransactionName(string $name): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setTransactionName($name);
        }
    }

    /**
     * @return void
     */
    public function markStartTransaction(): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markStartTransaction();
        }
    }

    /**
     * @return void
     */
    public function markEndOfTransaction(): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markEndOfTransaction();
        }
    }

    /**
     * @return void
     */
    public function markIgnoreTransaction(): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markIgnoreTransaction();
        }
    }

    /**
     * @return void
     */
    public function markAsConsoleCommand(): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markAsConsoleCommand();
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addCustomParameter(string $key, $value): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->addCustomParameter($key, $value);
        }
    }

    /**
     * @param string $tracer
     *
     * @return void
     */
    public function addCustomTracer(string $tracer): void
    {
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->addCustomTracer($tracer);
        }
    }
}
