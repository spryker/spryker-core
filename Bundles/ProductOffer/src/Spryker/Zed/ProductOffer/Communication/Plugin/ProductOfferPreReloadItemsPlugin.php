<?php
/**
 * Created by PhpStorm.
 * User: smarovydlo
 * Date: 11/29/19
 * Time: 5:01 PM
 */

namespace Spryker\ProductOffer\src\Spryker\Zed\ProductOffer\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;

class ProductOfferPreReloadItemsPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates item identifier for product offer items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getProductOffer()) {
                continue;
            }

            $itemTransfer->setItemIdentifier(
                $itemTransfer->getProductOffer()->getProductOfferReference()
            );
        }

        return $quoteTransfer;
    }
}
