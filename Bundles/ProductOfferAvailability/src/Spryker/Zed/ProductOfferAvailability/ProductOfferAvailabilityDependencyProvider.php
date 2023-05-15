<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeBridge;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOfferAvailability\ProductOfferAvailabilityConfig getConfig()
 */
class ProductOfferAvailabilityDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER_STOCK = 'FACADE_PRODUCT_OFFER_STOCK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addOmsFacade($container);
        $container = $this->addProductOfferStockFacade($container);
        $container = $this->addProductOfferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return new ProductOfferAvailabilityToOmsFacadeBridge($container->getLocator()->oms()->facade());
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
            return new ProductOfferAvailabilityToProductOfferFacadeBridge($container->getLocator()->productOffer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER_STOCK, function (Container $container) {
            return new ProductOfferAvailabilityToProductOfferStockFacadeBridge($container->getLocator()->productOfferStock()->facade());
        });

        return $container;
    }
}
