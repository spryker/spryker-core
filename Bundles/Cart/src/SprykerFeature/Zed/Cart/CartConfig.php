<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;

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