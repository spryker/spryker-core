<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Search\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Service\Search\SearchService getService()
 */
class SearchHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    protected const SERVICE_NAME = 'search';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::SERVICE_NAME;
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
        return $this->getService()->checkSearchHealthIndicator();
    }
}
