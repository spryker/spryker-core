<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Communication\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\PublishAndSynchronizeHealthCheckStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig getConfig()
 */
class PublishAndSynchronizeHealthCheckStoragePlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    /**
     * @var string
     */
    protected const PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_STORAGE_SERVICE_NAME = 'publish-and-synchronize-storage';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_STORAGE_SERVICE_NAME;
    }

    /**
     * {@inheritDoc}
     * - Returns a successful `HealthCheckServiceResponseTransfer` when the data received from the storage is not older than the configured threshold.
     * - Returns a failed `HealthCheckServiceResponseTransfer` when no data was received from the storage or when the data is older than the configured threshold.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function check(): HealthCheckServiceResponseTransfer
    {
        return $this->getFacade()->performHealthCheck();
    }
}
