<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\QuoteItemsPreConvertPluginInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 */
class ReplaceBundledQuoteItemsPreConvertPlugin extends AbstractPlugin implements QuoteItemsPreConvertPluginInterface
{
    /**
     * Specification:
     *  - Replace bundled items with bundle items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function expand(ItemCollectionTransfer $itemCollectionTransfer, QuoteTransfer $quoteTransfer): ItemCollectionTransfer
    {
        return $this->getFacade()
            ->extractQuoteItems($quoteTransfer);
    }
}
