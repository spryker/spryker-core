<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Storage\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;

/**
 * @method \Spryker\Yves\Storage\StorageFactory getFactory()
 */
class KeyValueStoreHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    protected const STORAGE_HEALTH_CHECK_SERVICE_NAME = 'storage';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::STORAGE_HEALTH_CHECK_SERVICE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function check(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createKeyValueStoreHealthChecker()->executeHealthCheck();
    }
}
