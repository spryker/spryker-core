<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationMerchantPortalGui;

use Spryker\Zed\AuthenticationMerchantPortalGui\Dependency\Facade\AuthenticationMerchantPortalGuiToAuthFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AuthenticationMerchantPortalGui\AuthenticationMerchantPortalGuiConfig getConfig()
 */
class AuthenticationMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_AUTH = 'FACADE_AUTH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addAuthFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAuthFacade(Container $container): Container
    {
        $container->set(static::FACADE_AUTH, function (Container $container) {
            return new AuthenticationMerchantPortalGuiToAuthFacadeBridge($container->getLocator()->auth()->facade());
        });

        return $container;
    }
}
