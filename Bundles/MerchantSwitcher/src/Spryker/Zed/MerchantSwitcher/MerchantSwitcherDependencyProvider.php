<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToCartFacadeBridge;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeBridge;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToQuoteFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 */
class MerchantSwitcherDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const FACADE_CART = 'FACADE_CART';
    public const FACADE_MERCHANT_PRODUCT_OFFER = 'FACADE_MERCHANT_PRODUCT_OFFER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addQuoteFacade($container);
        $container = $this->addCartFacade($container);
        $container = $this->addMerchantProductOfferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container): Container
    {
        $container->set(static::FACADE_QUOTE, function (Container $container) {
            return new MerchantSwitcherToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container): Container
    {
        $container->set(static::FACADE_CART, function (Container $container) {
            return new MerchantSwitcherToCartFacadeBridge($container->getLocator()->cart()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_PRODUCT_OFFER, function (Container $container) {
            return new MerchantSwitcherToMerchantProductOfferFacadeBridge($container->getLocator()->merchantProductOffer()->facade());
        });

        return $container;
    }
}
