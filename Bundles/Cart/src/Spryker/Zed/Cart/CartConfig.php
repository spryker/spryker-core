<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CartConfig extends AbstractBundleConfig
{

    /**
     * @return \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    public function getCartItemPlugins()
    {
        return [];
    }

}
