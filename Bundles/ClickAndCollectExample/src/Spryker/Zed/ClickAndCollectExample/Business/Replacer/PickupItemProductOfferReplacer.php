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
use Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteReplacementResponseErrorAdderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface;
use Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig;

class PickupItemProductOfferReplacer implements ItemProductOfferReplacerInterface
{
    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface
     */
    protected ProductOfferServicePointReaderInterface $productOfferServicePointReader;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface
     */
    protected ProductOfferReplacementFinderInterface $replacementFinder;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteReplacementResponseErrorAdderInterface
     */
    protected QuoteReplacementResponseErrorAdderInterface $quoteReplacementResponseErrorAdder;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig
     */
    protected ClickAndCollectExampleConfig $clickAndCollectExampleConfig;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface $productOfferServicePointReader
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface $replacementFinder
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteReplacementResponseErrorAdderInterface $quoteReplacementResponseErrorAdder
     * @param \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig $clickAndCollectExampleConfig
     */
    public function __construct(
        ProductOfferServicePointReaderInterface $productOfferServicePointReader,
        ProductOfferReplacementFinderInterface $replacementFinder,
        QuoteReplacementResponseErrorAdderInterface $quoteReplacementResponseErrorAdder,
        ClickAndCollectExampleConfig $clickAndCollectExampleConfig
    ) {
        $this->productOfferServicePointReader = $productOfferServicePointReader;
        $this->replacementFinder = $replacementFinder;
        $this->quoteReplacementResponseErrorAdder = $quoteReplacementResponseErrorAdder;
        $this->clickAndCollectExampleConfig = $clickAndCollectExampleConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function replaceQuoteItemProductOffers(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer
    {
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())->setQuote($quoteTransfer);
        $itemTransfersForReplacement = $this->getQuoteItemsAvailableForReplacement($quoteReplacementResponseTransfer);
        if (count($itemTransfersForReplacement) === 0) {
            return $quoteReplacementResponseTransfer;
        }
        $productOfferServicePointCriteriaTransfer = $this
            ->createProductOfferServicePointCriteriaTransfer($quoteTransfer, $itemTransfersForReplacement);

        $productOfferServicePointTransfers = $this->productOfferServicePointReader
            ->getPickupProductOfferServicePoints($productOfferServicePointCriteriaTransfer);

        foreach ($itemTransfersForReplacement as $itemTransfer) {
            $replacementProductOfferTransfer = $this->replacementFinder
                ->findSuitableProductOffer($itemTransfer, $productOfferServicePointTransfers);
            if (!$replacementProductOfferTransfer) {
                $quoteReplacementResponseTransfer->addFailedReplacementItem($itemTransfer);
                $this->quoteReplacementResponseErrorAdder->addError($quoteReplacementResponseTransfer, $itemTransfer);

                continue;
            }

            $itemTransfer->setProductOfferReference($replacementProductOfferTransfer->getProductOfferReference());
            $itemTransfer->setGroupKey(null);
        }

        return $quoteReplacementResponseTransfer;
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

            if (!$this->isQuoteItemValid($itemTransfer)) {
                $quoteReplacementResponseTransfer->addFailedReplacementItem($itemTransfer);
                $this->quoteReplacementResponseErrorAdder->addError($quoteReplacementResponseTransfer, $itemTransfer);

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
            && $itemTransfer->getShipmentTypeOrFail()->getKey() === $this->clickAndCollectExampleConfig->getPickupShipmentTypeKey();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isQuoteItemValid(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipmentType() !== null
            && $itemTransfer->getServicePoint() !== null
            && $itemTransfer->getProductOfferReference() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $quoteItemsForReplacement
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer
     */
    protected function createProductOfferServicePointCriteriaTransfer(
        QuoteTransfer $quoteTransfer,
        array $quoteItemsForReplacement
    ): ProductOfferServicePointCriteriaTransfer {
        $productOfferServicePointCriteriaTransfer = (new ProductOfferServicePointCriteriaTransfer())
            ->setStoreName($quoteTransfer->getStoreOrFail()->getNameOrFail())
            ->setShipmentTypeKey($this->clickAndCollectExampleConfig->getPickupShipmentTypeKey())
            ->setCurrencyCode($quoteTransfer->getCurrencyOrFail()->getCodeOrFail())
            ->setPriceMode($quoteTransfer->getPriceModeOrFail());

        foreach ($quoteItemsForReplacement as $itemTransfer) {
            $productOfferServicePointCriteriaTransfer
                ->addConcreteSku($itemTransfer->getSkuOrFail())
                ->addIdServicePoint($itemTransfer->getServicePointOrFail()->getIdServicePointOrFail());
        }

        return $productOfferServicePointCriteriaTransfer;
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
}
