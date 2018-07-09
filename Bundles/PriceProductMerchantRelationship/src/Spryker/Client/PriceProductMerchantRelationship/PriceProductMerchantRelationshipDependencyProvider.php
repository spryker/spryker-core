<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationship;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\PriceProductMerchantRelationship\Dependency\Client\PriceProductMerchantRelationshipToCartClientBridge;

class PriceProductMerchantRelationshipDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    private function addCartClient(Container $container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new PriceProductMerchantRelationshipToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }
}
