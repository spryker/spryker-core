<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\CartChangeItemExpanderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageFactory getFactory()
 */
class CartChangeItemQuantityExpanderPlugin extends AbstractPlugin implements CartChangeItemExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adjusts ItemTransfer quantity according to restrictions.
     * - Adds quantity adjustment info messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransfer(ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getFactory()
            ->createQuantityCartChangeItemExpander()
            ->expandCartChangeItem($itemTransfer);
    }
}
