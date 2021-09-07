<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ZedRequest\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;

/**
 * @method \Spryker\Glue\ZedRequest\ZedRequestFactory getFactory()
 */
class ZedRequestHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    /**
     * @var string
     */
    public const ZED_REQUEST_HEALTH_CHECK_SERVICE_NAME = 'zed-request';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::ZED_REQUEST_HEALTH_CHECK_SERVICE_NAME;
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
        return $this->getFactory()->createZedRequestHealthChecker()->executeHealthCheck();
    }
}
