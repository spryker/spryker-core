<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidityGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferValidityGui\Dependency\Facade\ProductOfferValidityGuiToProductOfferValidityFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOfferValidityGui\ProductOfferValidityGuiConfig getConfig()
 */
class ProductOfferValidityGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_OFFER_VALIDITY = 'FACADE_PRODUCT_OFFER_VALIDITY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addProductOfferValidityFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferValidityFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER_VALIDITY, function (Container $container) {
            return new ProductOfferValidityGuiToProductOfferValidityFacadeBridge(
                $container->getLocator()->productOfferValidity()->facade()
            );
        });

        return $container;
    }
}
