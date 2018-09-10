<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Monitoring\Model;

class Monitoring implements MonitoringInterface
{
    /**
     * @var \Spryker\Service\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface[]
     */
    protected $monitoringExtensionPlugins;

    /**
     * @param \Spryker\Service\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface[] $monitoringExtensionPlugins
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
        $this->setApplicationName();

        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setError($message, $exception);
        }
    }

    /**
     * @param string|null $application
     * @param string|null $store
     * @param string|null $environment
     *
     * @return void
     */
    public function setApplicationName(?string $application = null, ?string $store = null, ?string $environment = null): void
    {
        $application = $application ?: APPLICATION;
        $store = $store ?: APPLICATION_STORE;
        $environment = $environment ?: APPLICATION_ENV;

        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setApplicationName($application, $store, $environment);
        }
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setTransactionName(string $name): void
    {
        $this->setApplicationName();

        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setTransactionName($name);
        }
    }

    /**
     * @return void
     */
    public function markStartTransaction(): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markStartTransaction();
        }
    }

    /**
     * @return void
     */
    public function markEndOfTransaction(): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markEndOfTransaction();
        }
    }

    /**
     * @return void
     */
    public function markIgnoreTransaction(): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markIgnoreTransaction();
        }
    }

    /**
     * @return void
     */
    public function markAsConsoleCommand(): void
    {
        $this->setApplicationName();
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
        $this->setApplicationName();
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
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->addCustomTracer($tracer);
        }
    }
}
