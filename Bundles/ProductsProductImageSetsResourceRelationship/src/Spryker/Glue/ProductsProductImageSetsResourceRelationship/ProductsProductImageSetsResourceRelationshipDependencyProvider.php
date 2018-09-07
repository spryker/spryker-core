<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource\ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiBridge;

class ProductsProductImageSetsResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_PRODUCT_IMAGE_SETS = 'RESOURCE_PRODUCT_IMAGE_SETS';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductImageSetsResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductImageSetsResource(Container $container): Container
    {
        $container[static::RESOURCE_PRODUCT_IMAGE_SETS] = function (Container $container) {
            return new ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiBridge(
                $container->getLocator()->productImageSetsRestApi()->resource()
            );
        };

        return $container;
    }
}
