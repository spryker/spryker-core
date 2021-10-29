<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi;

use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeBridge;

/**
 * @method \Spryker\Zed\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 */
class WishlistsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_WISHLIST = 'PROPEL_QUERY_WISHLIST';

    /**
     * @var string
     */
    public const FACADE_WISHLIST = 'FACADE_WISHLIST';

    /**
     * @var string
     */
    public const PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_DELETE_STRATEGY = 'PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_DELETE_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_UPDATE_STRATEGY = 'PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_UPDATE_STRATEGY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addWishlistPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addWishlistFacade($container);
        $container = $this->addRestWishlistItemsAttributesDeleteStrategyPlugins($container);
        $container = $this->addRestWishlistItemsAttributesUpdateStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addWishlistPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_WISHLIST, $container->factory(function () {
            return SpyWishlistQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addWishlistFacade(Container $container): Container
    {
        $container->set(static::FACADE_WISHLIST, function (Container $container) {
            return new WishlistsRestApiToWishlistFacadeBridge(
                $container->getLocator()->wishlist()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addRestWishlistItemsAttributesDeleteStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_DELETE_STRATEGY, function (Container $container) {
            return $this->getRestWishlistItemsAttributesDeleteStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesDeleteStrategyPluginInterface>
     */
    protected function getRestWishlistItemsAttributesDeleteStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addRestWishlistItemsAttributesUpdateStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_UPDATE_STRATEGY, function () {
            return $this->getRestWishlistItemsAttributesUpdateStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesUpdateStrategyPluginInterface>
     */
    protected function getRestWishlistItemsAttributesUpdateStrategyPlugins(): array
    {
        return [];
    }
}
