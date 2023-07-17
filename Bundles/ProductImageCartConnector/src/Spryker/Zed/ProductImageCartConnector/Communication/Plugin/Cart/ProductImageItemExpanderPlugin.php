<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductImageCartConnector\Business\ProductImageCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductImageCartConnector\Communication\ProductImageCartConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductImageCartConnector\ProductImageCartConnectorConfig getConfig()
 */
class ProductImageItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ItemTransfer.id` and `ItemTransfer.productAbstractId` to be set for each `CartChangeTransfer.items`.
     * - Gets product image sets by concrete product IDs.
     * - If product image sets less than cart items - gets more product image sets by abstract product IDs.
     * - Expands `CartChangeTransfer.items` with product image sets.
     * - Returns the expanded `CartChangeTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->expandCartChangeItems($cartChangeTransfer);
    }
}
