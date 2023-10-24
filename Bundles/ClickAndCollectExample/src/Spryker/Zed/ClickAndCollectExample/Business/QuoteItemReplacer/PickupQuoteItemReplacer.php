<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\QuoteItemReplacer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteResponseErrorAdderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface;
use Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig;

class PickupQuoteItemReplacer implements QuoteItemReplacerInterface
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
     * @var \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteResponseErrorAdderInterface
     */
    protected QuoteResponseErrorAdderInterface $quoteResponseErrorAdder;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig
     */
    protected ClickAndCollectExampleConfig $clickAndCollectExampleConfig;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface $productOfferServicePointReader
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface $replacementFinder
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteResponseErrorAdderInterface $quoteResponseErrorAdder
     * @param \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig $clickAndCollectExampleConfig
     */
    public function __construct(
        ProductOfferServicePointReaderInterface $productOfferServicePointReader,
        ProductOfferReplacementFinderInterface $replacementFinder,
        QuoteResponseErrorAdderInterface $quoteResponseErrorAdder,
        ClickAndCollectExampleConfig $clickAndCollectExampleConfig
    ) {
        $this->productOfferServicePointReader = $productOfferServicePointReader;
        $this->replacementFinder = $replacementFinder;
        $this->quoteResponseErrorAdder = $quoteResponseErrorAdder;
        $this->clickAndCollectExampleConfig = $clickAndCollectExampleConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteItemProductOffers(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = (new QuoteResponseTransfer())->setQuoteTransfer($quoteTransfer)->setIsSuccessful(true);
        $itemTransfersForReplacement = $this->getQuoteItemsAvailableForReplacement($quoteResponseTransfer);
        if (count($itemTransfersForReplacement) === 0) {
            return $quoteResponseTransfer;
        }
        $productOfferServicePointCriteriaTransfer = $this
            ->createProductOfferServicePointCriteriaTransfer($quoteTransfer, $itemTransfersForReplacement);

        $productOfferServicePointTransfers = $this->productOfferServicePointReader
            ->getPickupProductOfferServicePoints($productOfferServicePointCriteriaTransfer);

        foreach ($itemTransfersForReplacement as $itemTransfer) {
            $replacementProductOfferTransfer = $this->replacementFinder
                ->findSuitableProductOffer($itemTransfer, $productOfferServicePointTransfers);
            if (!$replacementProductOfferTransfer) {
                $itemTransfer->setShipmentType()->setShipment()->setServicePoint();
                $this->quoteResponseErrorAdder->addError($quoteResponseTransfer, $itemTransfer);

                continue;
            }

            $itemTransfer->setProductOfferReference($replacementProductOfferTransfer->getProductOfferReference());
            $itemTransfer->setGroupKey(null);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getQuoteItemsAvailableForReplacement(QuoteResponseTransfer $quoteResponseTransfer): array
    {
        $quoteItemTransfersForReplacement = [];
        foreach ($quoteResponseTransfer->getQuoteTransferOrFail()->getItems() as $itemTransfer) {
            if (!$this->isQuoteItemApplicable($itemTransfer)) {
                continue;
            }

            if (!$this->isQuoteItemValid($itemTransfer)) {
                $itemTransfer->setShipmentType(null)->setShipment(null)->setServicePoint(null);
                $this->quoteResponseErrorAdder->addError($quoteResponseTransfer, $itemTransfer);

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
        return !$this->isItemProductBundle($itemTransfer)
            && $itemTransfer->getShipmentType() !== null
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
