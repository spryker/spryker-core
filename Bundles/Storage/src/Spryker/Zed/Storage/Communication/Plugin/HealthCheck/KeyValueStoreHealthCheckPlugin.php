<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Communication\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Storage\Communication\StorageCommunicationFactory getFactory()()
 * @method \Spryker\Zed\Storage\StorageConfig getConfig()
 * @method \Spryker\Zed\Storage\Business\StorageFacadeInterface getFacade()
 */
class KeyValueStoreHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    public const STORAGE_HEALTH_CHECK_SERVICE_NAME = 'storage';

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
        return $this->getFacade()->executeKeyValueStoreHealthCheck();
    }
}
