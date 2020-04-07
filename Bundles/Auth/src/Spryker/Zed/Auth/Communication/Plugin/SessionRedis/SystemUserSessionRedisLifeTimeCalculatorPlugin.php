<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Plugin\SessionRedis;

use Generated\Shared\Transfer\HttpRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacadeInterface getFacade()
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface getQueryContainer()
 */
class SystemUserSessionRedisLifeTimeCalculatorPlugin extends AbstractPlugin implements SessionRedisLifeTimeCalculatorPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Returns true if auth token exists in `HttpRequestTransfer.headers` and it belongs to the system user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(HttpRequestTransfer $httpRequestTransfer): bool
    {
        return $this->getFacade()->isSystemUserRequest($httpRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *  - Returns redis session life time for system users.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return int
     */
    public function getLifeTime(HttpRequestTransfer $httpRequestTransfer): int
    {
        return $this->getConfig()->getSystemUserSessionRedisLifeTime();
    }
}
