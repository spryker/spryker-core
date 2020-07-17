<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleBridge;

/**
 * @method \Spryker\Zed\ProductOfferGui\ProductOfferGuiConfig getConfig()
 */
class ProductOfferGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_PRODUCT_OFFER_QUERY = 'PROPEL_PRODUCT_OFFER_QUERY';

    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addPropelProductOfferQuery($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelProductOfferQuery(Container $container): Container
    {
        $container->set(static::PROPEL_PRODUCT_OFFER_QUERY, $container->factory(function () {
            return SpyProductOfferQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, $container->factory(function (Container $container) {
            return new ProductOfferGuiToLocaleBridge($container->getLocator()->locale()->facade());
        }));

        return $container;
    }
}
