<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;

class CartConfig extends AbstractBundleConfig
{

    /**
     * @return ItemExpanderPluginInterface[]
     */
    public function getCartItemPlugins()
    {
        return [];
    }

}
