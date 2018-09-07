<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductPricesResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource\ProductsProductPricesResourceRelationToProductPricesRestApiBridge;

class ProductsProductPricesResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_PRODUCT_PRICES = 'RESOURCE_PRODUCT_PRICES';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductPricesResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductPricesResource(Container $container): Container
    {
        $container[static::RESOURCE_PRODUCT_PRICES] = function (Container $container) {
            return new ProductsProductPricesResourceRelationToProductPricesRestApiBridge(
                $container->getLocator()->productPricesRestApi()->resource()
            );
        };

        return $container;
    }
}
