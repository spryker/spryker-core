<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferReservationGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferReservationGui\Dependency\Facade\ProductOfferReservationGuiToOmsProductOfferReservationFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOfferReservationGui\ProductOfferReservationGuiConfig getConfig()
 */
class ProductOfferReservationGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_OMS_PRODUCT_OFFER_RESERVATION = 'FACADE_OMS_PRODUCT_OFFER_RESERVATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addOmsProductOfferReservationFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsProductOfferReservationFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS_PRODUCT_OFFER_RESERVATION, function (Container $container) {
            return new ProductOfferReservationGuiToOmsProductOfferReservationFacadeBridge(
                $container->getLocator()->omsProductOfferReservation()->facade()
            );
        });

        return $container;
    }
}
