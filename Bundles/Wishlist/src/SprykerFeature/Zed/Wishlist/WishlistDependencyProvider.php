<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class WishlistDependencyProvider extends AbstractBundleDependencyProvider
{
    const PRODUCT_FACADE = 'product_facade';
    const PRE_SAVE_PLUGINS = 'pre_save_plugins';
    const POST_SAVE_PLUGINS = 'post_save_plugins';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::PRODUCT_FACADE] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        $container[self::PRE_SAVE_PLUGINS] = function (Container $container) {
            return $this->preSavePlugins($container);
        };

        $container[self::POST_SAVE_PLUGINS] = function (Container $container) {
            return $this->postSavePlugins($container);
        };


        return $container;
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    public function preSavePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    public function postSavePlugins(Container $container)
    {
        return [];
    }
}
