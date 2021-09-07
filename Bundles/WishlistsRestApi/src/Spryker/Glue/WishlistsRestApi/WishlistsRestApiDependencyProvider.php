<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientBridge;

/**
 * @method \Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 */
class WishlistsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_WISHLIST = 'CLIENT_WISHLIST';
    /**
     * @var string
     */
    public const PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_MAPPER = 'PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addWishlistClient($container);
        $container = $this->addRestWishlistItemsAttributesMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addWishlistClient(Container $container): Container
    {
        $container->set(static::CLIENT_WISHLIST, function (Container $container) {
            return new WishlistsRestApiToWishlistClientBridge($container->getLocator()->wishlist()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestWishlistItemsAttributesMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_MAPPER, function (Container $container) {
            return $this->getRestWishlistItemsAttributesMapperPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesMapperPluginInterface[]
     */
    protected function getRestWishlistItemsAttributesMapperPlugins(): array
    {
        return [];
    }
}
