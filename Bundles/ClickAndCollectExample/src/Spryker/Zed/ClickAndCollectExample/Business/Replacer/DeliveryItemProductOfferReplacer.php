<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Replacer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class DeliveryItemProductOfferReplacer extends AbstractItemProductOfferReplacer
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, \Generated\Shared\Transfer\ItemTransfer> $itemTransfersForReplacement
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    protected function getProductOfferServicePointTransfers(QuoteTransfer $quoteTransfer, array $itemTransfersForReplacement): array
    {
        $productOfferServicePointCriteriaTransfer = $this
            ->createProductOfferServicePointCriteriaTransfer($quoteTransfer, $itemTransfersForReplacement);

        return $this->productOfferServicePointReader
            ->getDeliveryProductOfferServicePoints($productOfferServicePointCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getQuoteItemsAvailableForReplacement(QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer): array
    {
        $quoteItemTransfersForReplacement = [];
        foreach ($quoteReplacementResponseTransfer->getQuoteOrFail()->getItems() as $itemTransfer) {
            if (!$this->isQuoteItemApplicable($itemTransfer)) {
                continue;
            }

            $quoteItemTransfersForReplacement[] = $itemTransfer;
        }

        return $quoteItemTransfersForReplacement;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isQuoteItemApplicable(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipmentType()
            && !$this->isItemProductBundle($itemTransfer)
            && $itemTransfer->getProductOfferReference()
            && $itemTransfer->getShipmentTypeOrFail()->getKey() === $this->clickAndCollectExampleConfig->getDeliveryShipmentTypeKey();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemProductBundle(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getRelatedBundleItemIdentifier() || $itemTransfer->getBundleItemIdentifier();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, \Generated\Shared\Transfer\ItemTransfer> $quoteItemTransfersForReplacement
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer
     */
    protected function createProductOfferServicePointCriteriaTransfer(
        QuoteTransfer $quoteTransfer,
        array $quoteItemTransfersForReplacement
    ): ProductOfferServicePointCriteriaTransfer {
        $productOfferServicePointCriteriaTransfer = (new ProductOfferServicePointCriteriaTransfer())
            ->setStoreName($quoteTransfer->getStoreOrFail()->getNameOrFail())
            ->setShipmentTypeKey($this->clickAndCollectExampleConfig->getDeliveryShipmentTypeKey())
            ->setCurrencyCode($quoteTransfer->getCurrencyOrFail()->getCodeOrFail())
            ->setPriceMode($quoteTransfer->getPriceModeOrFail())
            ->setIsActive(true);

        if (!$this->isFilteredByIsActive($quoteTransfer)) {
            $productOfferServicePointCriteriaTransfer->setIsActive(false);
        }

        foreach ($quoteItemTransfersForReplacement as $itemTransfer) {
            $productOfferServicePointCriteriaTransfer
                ->addConcreteSku($itemTransfer->getSkuOrFail());
        }

        return $productOfferServicePointCriteriaTransfer;
    }
}
