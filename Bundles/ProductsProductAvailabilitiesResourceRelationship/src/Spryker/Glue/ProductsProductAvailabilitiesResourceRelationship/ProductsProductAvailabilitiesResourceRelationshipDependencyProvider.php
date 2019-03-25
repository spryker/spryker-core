<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Dependency\RestResource\ProductsResourceRelationToProductAvailabilitiesRestApiBridge;

/**
 * @method \Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\ProductsProductAvailabilitiesResourceRelationshipConfig getConfig()
 */
class ProductsProductAvailabilitiesResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_PRODUCT_AVAILABILITIES = 'RESOURCE_PRODUCT_AVAILABILITIES';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductAvailabilitiesResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductAvailabilitiesResource(Container $container): Container
    {
        $container[static::RESOURCE_PRODUCT_AVAILABILITIES] = function (Container $container) {
            return new ProductsResourceRelationToProductAvailabilitiesRestApiBridge(
                $container->getLocator()->productAvailabilitiesRestApi()->resource()
            );
        };

        return $container;
    }
}
