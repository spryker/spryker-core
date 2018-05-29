<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MonitoringExtension;

interface MonitoringInterface
{
    /**
     * @param string $message
     * @param \Exception|\Throwable $exception
     *
     * @return void
     */
    public function setError(string $message, $exception): void;

    /**
     * Sets the name of the application, the current store and the environment.
     *
     * @param string $application
     * @param string $store
     * @param string $environment
     *
     * @return void
     */
    public function setAppName(string $application, string $store, string $environment): void;

    /**
     * Name of the transaction (e.g. module/controller/action).
     *
     * @param string $name
     *
     * @return void
     */
    public function setTransactionName(string $name): void;

    /**
     * Start recording of the current transaction.
     *
     * @return void
     */
    public function markStartTransaction(): void;

    /**
     * Stop recording the web transaction. This can be used to exclude time consuming operations that happen after
     * the request is completed.
     *
     * @return void
     */
    public function markEndOfTransaction(): void;

    /**
     * Do not generate metrics for this transaction. This can be used for operations that are not relevant for the
     * statistics (e.g. to exclude the load balancer heartbeat check or very time consuming operations).
     *
     * @return void
     */
    public function markIgnoreTransaction(): void;

    /**
     * Marks this transaction as a console command (e.g. for cronjobs)
     *
     * @return void
     */
    public function markAsConsoleCommand(): void;

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addCustomParameter(string $key, $value): void;

    /**
     * @param string $tracer classname::function_name.
     *
     * @return void
     */
    public function addCustomTracer(string $tracer): void;
}
