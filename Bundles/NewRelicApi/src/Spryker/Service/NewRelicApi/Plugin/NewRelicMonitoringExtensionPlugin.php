<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\NewRelicApi\Plugin;

use Spryker\Service\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface;

/**
 * @deprecated Use SprykerEco\Service\NewRelic\Plugin\NewRelicMonitoringExtensionPlugin instead
 */
class NewRelicMonitoringExtensionPlugin implements MonitoringExtensionPluginInterface
{
    /**
     * @var string
     */
    protected $application;

    /**
     * @var bool
     */
    protected $isActive;

    public function __construct()
    {
        $this->isActive = extension_loaded('newrelic');
    }

    /**
     * @param string $message
     * @param \Exception|\Throwable $exception
     *
     * @return void
     */
    public function setError(string $message, $exception): void
    {
        if (!$this->isActive) {
            return;
        }

        newrelic_notice_error($message, $exception);
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
        if (!$this->isActive) {
            return;
        }

        $this->application = $application . '-' . $store . ' (' . $environment . ')';

        newrelic_set_appname($this->application);
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setTransactionName(string $name): void
    {
        if (!$this->isActive) {
            return;
        }

        newrelic_name_transaction($name);
    }

    /**
     * @return void
     */
    public function markStartTransaction(): void
    {
        if (!$this->isActive) {
            return;
        }

        newrelic_start_transaction($this->application);
    }

    /**
     * @return void
     */
    public function markEndOfTransaction(): void
    {
        if (!$this->isActive) {
            return;
        }

        newrelic_end_transaction();
    }

    /**
     * @return void
     */
    public function markIgnoreTransaction(): void
    {
        if (!$this->isActive) {
            return;
        }

        newrelic_ignore_transaction();
    }

    /**
     * @return void
     */
    public function markAsConsoleCommand(): void
    {
        if (!$this->isActive) {
            return;
        }

        newrelic_background_job(true);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addCustomParameter(string $key, $value): void
    {
        if (!$this->isActive) {
            return;
        }

        newrelic_add_custom_parameter($key, $value);
    }

    /**
     * @param string $tracer
     *
     * @return void
     */
    public function addCustomTracer(string $tracer): void
    {
        if (!$this->isActive) {
            return;
        }

        newrelic_add_custom_tracer($tracer);
    }
}
