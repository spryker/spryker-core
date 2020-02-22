<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShopContext\Communication\Provider;

use Generated\Shared\Transfer\ShopContextTransfer;

class ShopContextProvider implements ShopContextProviderInterface
{
    /**
     * @var \Spryker\Shared\ShopContextExtension\Dependency\Plugin\ShopContextExpanderPluginInterface[]
     */
    protected $shopContextExpanderPlugins;

    /**
     * @param \Spryker\Shared\ShopContextExtension\Dependency\Plugin\ShopContextExpanderPluginInterface[] $shopContextExpanderPlugins
     */
    public function __construct(array $shopContextExpanderPlugins)
    {
        $this->shopContextExpanderPlugins = $shopContextExpanderPlugins;
    }

    /**
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function provide(): ShopContextTransfer
    {
        $shopContextTransfer = new ShopContextTransfer();

        foreach ($this->shopContextExpanderPlugins as $shopContextExpanderPlugin) {
            $shopContextTransfer = $shopContextExpanderPlugin->expand($shopContextTransfer);
        }

        return $shopContextTransfer;
    }
}
