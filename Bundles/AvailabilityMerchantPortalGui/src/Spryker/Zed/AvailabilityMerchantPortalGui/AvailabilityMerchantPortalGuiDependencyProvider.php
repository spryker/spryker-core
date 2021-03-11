<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityMerchantPortalGui;

use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToAvailabilityFacadeBridge;
use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantStockFacadeBridge;
use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_MERCHANT_STOCK = 'FACADE_MERCHANT_STOCK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addAvailabilityFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addMerchantStockFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAvailabilityFacade(Container $container): Container
    {
        $container->set(static::FACADE_AVAILABILITY, function (Container $container) {
            return new AvailabilityMerchantPortalGuiToAvailabilityFacadeBridge(
                $container->getLocator()->availability()->facade()
            );
        });

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
            return new AvailabilityMerchantPortalGuiToMerchantUserFacadeBridge(
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
    protected function addMerchantStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_STOCK, function (Container $container) {
            return new AvailabilityMerchantPortalGuiToMerchantStockFacadeBridge(
                $container->getLocator()->merchantStock()->facade()
            );
        });

        return $container;
    }
}
