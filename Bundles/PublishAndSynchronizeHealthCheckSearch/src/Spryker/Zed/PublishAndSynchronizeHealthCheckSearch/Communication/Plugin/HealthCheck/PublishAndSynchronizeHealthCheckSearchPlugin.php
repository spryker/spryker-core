<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Communication\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\PublishAndSynchronizeHealthCheckSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig getConfig()
 */
class PublishAndSynchronizeHealthCheckSearchPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    protected const PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SEARCH_SERVICE_NAME = 'publish-and-synchronize-search';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SEARCH_SERVICE_NAME;
    }

    /**
     * {@inheritDoc}
     * - Returns a successful `HealthCheckServiceResponseTransfer` when the data received from the search is not older than the configured threshold.
     * - Returns a failed `HealthCheckServiceResponseTransfer` when no data was received from the search or when the data is older than the configured threshold.
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
