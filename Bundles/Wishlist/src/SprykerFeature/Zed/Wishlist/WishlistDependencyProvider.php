<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;

class WishlistDependencyProvider extends AbstractBundleDependencyProvider
{
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
