<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Expander;

use Generated\Shared\Transfer\ItemTransfer;

class CartChangeItemExpander implements CartChangeItemExpanderInterface
{
    /**
     * @var \Spryker\Client\CartExtension\Dependency\Plugin\CartChangeItemExpanderPluginInterface[]
     */
    protected $cartChangeItemExpanderPlugins;

    /**
     * @param \Spryker\Client\CartExtension\Dependency\Plugin\CartChangeItemExpanderPluginInterface[] $cartChangeItemExpanderPlugins
     */
    public function __construct(array $cartChangeItemExpanderPlugins)
    {
        $this->cartChangeItemExpanderPlugins = $cartChangeItemExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expand(ItemTransfer $itemTransfer): ItemTransfer
    {
        foreach ($this->cartChangeItemExpanderPlugins as $cartChangeItemExpanderPlugin) {
            $itemTransfer = $cartChangeItemExpanderPlugin->expandItemTransfer($itemTransfer);
        }

        return $itemTransfer;
    }
}
