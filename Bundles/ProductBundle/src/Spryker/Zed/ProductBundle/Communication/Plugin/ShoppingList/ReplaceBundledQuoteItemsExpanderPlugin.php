<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\QuoteItemsExtractorExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 */
class ReplaceBundledQuoteItemsExpanderPlugin extends AbstractPlugin implements QuoteItemsExtractorExpanderPluginInterface
{
    /**
     * Specification:
     *  - Replace bundled items with bundle items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemTransferCollection, QuoteTransfer $quoteTransfer): array
    {
        if (!$quoteTransfer->getBundleItems()) {
            return $itemTransferCollection;
        }

        return $this->getFacade()
            ->extractQuoteItems($quoteTransfer);
    }
}
