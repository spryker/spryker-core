<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui;

use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeBridge;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToUserFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantUserGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_MERCHANT_USER = 'PROPEL_MERCHANT_USER_QUERY';
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantUserPropelQuery($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_USER, $container->factory(function () {
            return SpyMerchantUserQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new MerchantUserGuiToMerchantUserFacadeBridge(
                $container->getLocator()->merchantUser()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new MerchantUserGuiToUserFacadeBridge(
                $container->getLocator()->user()->facade()
            );
        });

        return $container;
    }
}
