<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Monitoring;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Monitoring\MonitoringServiceFactory getFactory()
 */
class MonitoringService extends AbstractService implements MonitoringServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $message
     * @param \Exception|\Throwable $exception
     *
     * @return void
     */
    public function setError(string $message, $exception): void
    {
        $this->getFactory()->createMonitoring()->setError($message, $exception);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string|null $application
     * @param string|null $store
     * @param string|null $environment
     *
     * @return void
     */
    public function setApplicationName(?string $application = null, ?string $store = null, ?string $environment = null): void
    {
        $this->getFactory()->createMonitoring()->setApplicationName($application, $store, $environment);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return void
     */
    public function setTransactionName(string $name): void
    {
        $this->getFactory()->createMonitoring()->setTransactionName($name);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function markStartTransaction(): void
    {
        $this->getFactory()->createMonitoring()->markStartTransaction();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function markEndOfTransaction(): void
    {
        $this->getFactory()->createMonitoring()->markEndOfTransaction();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function markIgnoreTransaction(): void
    {
        $this->getFactory()->createMonitoring()->markIgnoreTransaction();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function markAsConsoleCommand(): void
    {
        $this->getFactory()->createMonitoring()->markAsConsoleCommand();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addCustomParameter(string $key, $value): void
    {
        $this->getFactory()->createMonitoring()->addCustomParameter($key, $value);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $tracer
     *
     * @return void
     */
    public function addCustomTracer(string $tracer): void
    {
        $this->getFactory()->createMonitoring()->addCustomTracer($tracer);
    }
}
