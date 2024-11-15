<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductList\Business\ProductListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 */
class ProductListRestrictedItemsCartPreReorderPlugin extends AbstractPlugin implements CartPreReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters out restricted items from the cart reorder request.
     * - Adds note to messages about removed items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function preReorder(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($cartReorderTransfer->getQuoteOrFail()->getCustomer())
            ->setItems($cartReorderTransfer->getOrderItems());

        $quoteTransfer = $this->getFacade()->filterRestrictedItems($quoteTransfer);

        return $cartReorderTransfer->setOrderItems($quoteTransfer->getItems());
    }
}
