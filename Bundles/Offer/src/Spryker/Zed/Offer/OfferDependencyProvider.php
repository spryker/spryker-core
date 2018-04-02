<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Offer\Communication\Plugin\OfferSavingHydratorPlugin;
use Spryker\Zed\Offer\Dependency\Facade\OfferToCartFacadeBridge;
use Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeBridge;

class OfferDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_CART = 'FACADE_CART';
    public const PLUGINS_OFFER_HYDRATOR = 'PLUGINS_OFFER_HYDRATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $this->addSalesFacade($container);
        $this->addOfferHydratorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addCartFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container[static::FACADE_SALES] = function (Container $container) {
            return new OfferToSalesFacadeBridge(
                $container->getLocator()->sales()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container): Container
    {
        $container[static::FACADE_CART] = function (Container $container) {
            return new OfferToCartFacadeBridge($container->getLocator()->cart()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOfferHydratorPlugins(Container $container)
    {
        $container[static::PLUGINS_OFFER_HYDRATOR] = function (Container $container) {
            return $this->getOfferHydratorPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface[]
     */
    protected function getOfferHydratorPlugins(): array
    {
        return [
            new OfferSavingHydratorPlugin(),
        ];
    }
}
