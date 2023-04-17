<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsProductsBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Dependency\Resource\PickingListsProductsBackendResourceRelationshipToProductsBackendApiResourceBridge;

/**
 * @method \Spryker\Glue\PickingListsProductsBackendResourceRelationship\PickingListsProductsBackendResourceRelationshipConfig getConfig()
 */
class PickingListsProductsBackendResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const RESOURCE_PRODUCTS_BACKEND_API = 'RESOURCE_PRODUCTS_BACKEND_API';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);

        $container = $this->addProductsBackendApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addProductsBackendApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_PRODUCTS_BACKEND_API, function (Container $container) {
            return new PickingListsProductsBackendResourceRelationshipToProductsBackendApiResourceBridge(
                $container->getLocator()->productsBackendApi()->resource(),
            );
        });

        return $container;
    }
}
