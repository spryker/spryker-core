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


        return $container;
    }

    /**
     * @return array
     */
    public function preSavePlugins()
    {
        return [];
    }

    /**
     * @return array
     */
    public function postSavePlugins()
    {
        return [];
    }
}
