<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantUserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        parent::provideBusinessLayerDependencies($container);

        $container = $this->addUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(self::FACADE_USER, function (Container $container) {
            $merchantUserToUserFacadeBridge = new MerchantUserToUserFacadeBridge(
                $container->getLocator()->user()->facade()
            );

            return $merchantUserToUserFacadeBridge;
        });

        return $container;
    }
}
