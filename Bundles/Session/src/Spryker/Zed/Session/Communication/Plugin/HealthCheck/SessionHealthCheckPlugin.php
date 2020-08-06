<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Session\Communication\SessionCommunicationFactory getFactory()()
 * @method \Spryker\Zed\Session\SessionConfig getConfig()
 * @method \Spryker\Zed\Session\Business\SessionFacadeInterface getFacade()
 */
class SessionHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    public const SESSION_HEALTH_CHECK_SERVICE_NAME = 'session';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::SESSION_HEALTH_CHECK_SERVICE_NAME;
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
        return $this->getFacade()->executeSessionHealthCheck();
    }
}
