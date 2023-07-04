<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig getConfig()
 */
class ProductOfferShipmentTypeDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SHIPMENT_TYPE = 'FACADE_SHIPMENT_TYPE';

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

        $container = $this->addShipmentTypeFacade($container);
        $container = $this->addProductOfferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentTypeFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT_TYPE, function (Container $container) {
            return new ProductOfferShipmentTypeToShipmentTypeFacadeBridge(
                $container->getLocator()->shipmentType()->facade(),
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
            return new ProductOfferShipmentTypeToProductOfferFacadeBridge(
                $container->getLocator()->productOffer()->facade(),
            );
        });

        return $container;
    }
}
