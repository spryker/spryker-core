<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Monitoring;

/**
 * @uses \Spryker\Service\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface
 */
interface MonitoringServiceInterface
{
    /**
     * Specification:
     *
     * @api
     *
     * @param string $message
     * @param \Exception|\Throwable $exception
     *
     * @return void
     */
    public function setError(string $message, $exception): void;

    /**
     * Specification:
     * - Sets the name of the application, the current store and the environment.
     *
     * @api
     *
     * @param string|null $application
     * @param string|null $store
     * @param string|null $environment
     *
     * @return void
     */
    public function setApplicationName(?string $application = null, ?string $store = null, ?string $environment = null): void;

    /**
     * Specification:
     * - Name of the transaction (e.g. module/controller/action).
     *
     * @api
     *
     * @param string $name
     *
     * @return void
     */
    public function setTransactionName(string $name): void;

    /**
     * Specification:
     * - Start recording of the current transaction.
     *
     * @api
     *
     * @return void
     */
    public function markStartTransaction(): void;

    /**
     * Specification:
     * - Stop recording the web transaction. This can be used to exclude time consuming operations that happen after
     * the request is completed.
     *
     * @api
     *
     * @return void
     */
    public function markEndOfTransaction(): void;

    /**
     * Specification:
     * - Do not generate metrics for this transaction. This can be used for operations that are not relevant for the
     * statistics (e.g. to exclude the load balancer heartbeat check or very time consuming operations).
     *
     * @api
     *
     * @return void
     */
    public function markIgnoreTransaction(): void;

    /**
     * Specification:
     * - Marks this transaction as a console command (e.g. for cronjobs).
     *
     * @api
     *
     * @return void
     */
    public function markAsConsoleCommand(): void;

    /**
     * Specification:
     *
     * @api
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addCustomParameter(string $key, $value): void;

    /**
     * Specification:
     *
     * @api
     *
     * @param string $tracer classname::function_name.
     *
     * @return void
     */
    public function addCustomTracer(string $tracer): void;
}
