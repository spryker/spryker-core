<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi;

use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Resource\ContentProductAbstractListsRestApiToProductsRestApiResourceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig getConfig()
 */
class ContentProductAbstractListsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONTENT_PRODUCT = 'CLIENT_CONTENT_PRODUCT';
    public const RESOURCE_PRODUCTS_REST_API = 'RESOURCE_PRODUCTS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addContentProductClient($container);
        $container = $this->addProductsRestApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addContentProductClient(Container $container): Container
    {
        $container[static::CLIENT_CONTENT_PRODUCT] = function (Container $container) {
            return new ContentProductAbstractListsRestApiToContentProductClientBridge(
                $container->getLocator()->contentProduct()->client()
            );
        };
        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductsRestApiResource(Container $container): Container
    {
        $container[static::RESOURCE_PRODUCTS_REST_API] = function (Container $container) {
            return new ContentProductAbstractListsRestApiToProductsRestApiResourceBridge(
                $container->getLocator()->productsRestApi()->resource()
            );
        };

        return $container;
    }
}
