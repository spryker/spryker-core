<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship;

use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToCatalogClientBridge;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\ConfigurableBundlesProductsResourceRelationshipConfig getConfig()
 */
class ConfigurableBundlesProductsResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_PRODUCTS_REST_API = 'RESOURCE_PRODUCTS_REST_API';

    public const CLIENT_CATALOG = 'CLIENT_CATALOG';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductsRestApiResource($container);
        $container = $this->addCatalogClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductsRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_PRODUCTS_REST_API, function (Container $container) {
            return new ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceBridge(
                $container->getLocator()->productsRestApi()->resource()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCatalogClient(Container $container): Container
    {
        $container->set(static::CLIENT_CATALOG, function (Container $container) {
            return new ConfigurableBundlesProductsResourceRelationshipToCatalogClientBridge(
                $container->getLocator()->catalog()->client()
            );
        });

        return $container;
    }
}
