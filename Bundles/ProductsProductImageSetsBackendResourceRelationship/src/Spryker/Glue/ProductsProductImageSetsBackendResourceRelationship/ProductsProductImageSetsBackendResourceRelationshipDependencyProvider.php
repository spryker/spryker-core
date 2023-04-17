<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Dependency\Resource\ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceBridge;

/**
 * @method \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\ProductsProductImageSetsBackendResourceRelationshipConfig getConfig()
 */
class ProductsProductImageSetsBackendResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const RESOURCE_PRODUCT_IMAGE_SETS_BACKEND_API = 'RESOURCE_PRODUCT_IMAGE_SETS_BACKEND_API';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addProductImageSetsBackendApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addProductImageSetsBackendApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_PRODUCT_IMAGE_SETS_BACKEND_API, function (Container $container) {
            return new ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceBridge(
                $container->getLocator()->productImageSetsBackendApi()->resource(),
            );
        });

        return $container;
    }
}
