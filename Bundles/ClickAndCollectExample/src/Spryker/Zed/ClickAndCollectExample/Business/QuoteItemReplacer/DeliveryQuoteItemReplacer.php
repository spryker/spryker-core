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

class DeliveryQuoteItemReplacer implements QuoteItemReplacerInterface
{
    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface
     */
    protected ProductOfferServicePointReaderInterface $clickAndCollectExampleProductOfferServicePointReader;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface
     */
    protected ProductOfferReplacementFinderInterface $clickAndCollectExampleProductOfferReplacementFinder;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteResponseErrorAdderInterface
     */
    protected QuoteResponseErrorAdderInterface $clickAndCollectExampleQuoteResponseErrorAdder;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig
     */
    protected ClickAndCollectExampleConfig $clickAndCollectExampleConfig;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface $clickAndCollectExampleProductOfferServicePointReader
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface $clickAndCollectExampleProductOfferReplacementFinder
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteResponseErrorAdderInterface $clickAndCollectExampleQuoteResponseErrorAdder
     * @param \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig $clickAndCollectExampleConfig
     */
    public function __construct(
        ProductOfferServicePointReaderInterface $clickAndCollectExampleProductOfferServicePointReader,
        ProductOfferReplacementFinderInterface $clickAndCollectExampleProductOfferReplacementFinder,
        QuoteResponseErrorAdderInterface $clickAndCollectExampleQuoteResponseErrorAdder,
        ClickAndCollectExampleConfig $clickAndCollectExampleConfig
    ) {
        $this->clickAndCollectExampleProductOfferServicePointReader = $clickAndCollectExampleProductOfferServicePointReader;
        $this->clickAndCollectExampleProductOfferReplacementFinder = $clickAndCollectExampleProductOfferReplacementFinder;
        $this->clickAndCollectExampleQuoteResponseErrorAdder = $clickAndCollectExampleQuoteResponseErrorAdder;
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

        $productOfferServicePointTransfers = $this->clickAndCollectExampleProductOfferServicePointReader
            ->getDeliveryProductOfferServicePoints($productOfferServicePointCriteriaTransfer);

        foreach ($itemTransfersForReplacement as $itemTransfer) {
            $replacementProductOfferTransfer = $this->clickAndCollectExampleProductOfferReplacementFinder->findSuitableProductOffer($itemTransfer, $productOfferServicePointTransfers);
            if (!$replacementProductOfferTransfer) {
                $itemTransfer->setShipmentType(null)->setShipment(null);
                $this->clickAndCollectExampleQuoteResponseErrorAdder->addError($quoteResponseTransfer, $itemTransfer);

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
            && $itemTransfer->getShipmentTypeOrFail()->getKey() === $this->clickAndCollectExampleConfig->getDeliveryShipmentTypeKey()
            && $itemTransfer->getProductOfferReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $quoteItemTransfersForReplacement
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
            ->setPriceMode($quoteTransfer->getPriceModeOrFail());

        foreach ($quoteItemTransfersForReplacement as $itemTransfer) {
            $productOfferServicePointCriteriaTransfer
                ->addConcreteSku($itemTransfer->getSkuOrFail());
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
