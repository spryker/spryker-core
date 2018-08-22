<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistItemsProductsResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiBridge;

class WishlistItemsProductsResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_PRODUCTS = 'RESOURCE_PRODUCTS';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductsResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductsResource(Container $container): Container
    {
        $container[static::RESOURCE_PRODUCTS] = function (Container $container) {
            return new WishlistItemsProductsResourceRelationshipToProductsRestApiBridge(
                $container->getLocator()->productsRestApi()->resource()
            );
        };

        return $container;
    }
}
