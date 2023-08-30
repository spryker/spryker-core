<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeAvailability;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeAvailability\ProductOfferShipmentTypeAvailabilityConfig getConfig()
 */
class ProductOfferShipmentTypeAvailabilityDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER_SHIPMENT_TYPE = 'FACADE_PRODUCT_OFFER_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addProductOfferShipmentTypeFacade($container);
        $container = $this->addProductOfferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferShipmentTypeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE, function (Container $container) {
            return new ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeBridge(
                $container->getLocator()->productOfferShipmentType()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER, function (Container $container) {
            return new ProductOfferShipmentTypeAvailabilityToProductOfferFacadeBridge(
                $container->getLocator()->productOffer()->facade(),
            );
        });

        return $container;
    }
}
