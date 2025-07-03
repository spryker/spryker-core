<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Replacer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteReplacementResponseErrorAdderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Merger\ItemMergerInterface;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface;
use Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig;

abstract class AbstractItemProductOfferReplacer implements ItemProductOfferReplacerInterface
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
     * @var \Spryker\Zed\ClickAndCollectExample\Business\Merger\ItemMergerInterface
     */
    protected ItemMergerInterface $itemMerger;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig
     */
    protected ClickAndCollectExampleConfig $clickAndCollectExampleConfig;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface $productOfferServicePointReader
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface $replacementFinder
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteReplacementResponseErrorAdderInterface $quoteReplacementResponseErrorAdder
     * @param \Spryker\Zed\ClickAndCollectExample\Business\Merger\ItemMergerInterface $itemMerger
     * @param \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig $clickAndCollectExampleConfig
     */
    public function __construct(
        ProductOfferServicePointReaderInterface $productOfferServicePointReader,
        ProductOfferReplacementFinderInterface $replacementFinder,
        QuoteReplacementResponseErrorAdderInterface $quoteReplacementResponseErrorAdder,
        ItemMergerInterface $itemMerger,
        ClickAndCollectExampleConfig $clickAndCollectExampleConfig
    ) {
        $this->productOfferServicePointReader = $productOfferServicePointReader;
        $this->replacementFinder = $replacementFinder;
        $this->quoteReplacementResponseErrorAdder = $quoteReplacementResponseErrorAdder;
        $this->itemMerger = $itemMerger;
        $this->clickAndCollectExampleConfig = $clickAndCollectExampleConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, \Generated\Shared\Transfer\ItemTransfer> $itemTransfersForReplacement
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    abstract protected function getProductOfferServicePointTransfers(QuoteTransfer $quoteTransfer, array $itemTransfersForReplacement): array;

    /**
     * @param \Generated\Shared\Transfer\QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    abstract protected function getQuoteItemsAvailableForReplacement(QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer): array;

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

        $mergedItemTransfersForReplacement = $this->itemMerger->mergeItemTransfersByCriteria($itemTransfersForReplacement);
        $productOfferServicePointTransfers = $this->getProductOfferServicePointTransfers(
            $quoteTransfer,
            $mergedItemTransfersForReplacement,
        );

        foreach ($mergedItemTransfersForReplacement as $itemTransfer) {
            $replacementProductOfferTransfer = $this->replacementFinder
                ->findSuitableProductOffer($itemTransfer, $quoteTransfer, $productOfferServicePointTransfers);
            if (!$replacementProductOfferTransfer) {
                $this->addFailedItemsToQuoteReplacementResponseTransfer(
                    $itemTransfer,
                    $quoteReplacementResponseTransfer,
                    $itemTransfersForReplacement,
                );

                continue;
            }

            $this->applyReplacementToOriginalItemTransfers(
                $replacementProductOfferTransfer,
                $itemTransfer,
                $itemTransfersForReplacement,
            );
        }

        return $quoteReplacementResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $replacementProductOfferTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $mergedItemTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $originalItemTransfers
     *
     * @return void
     */
    protected function applyReplacementToOriginalItemTransfers(
        ProductOfferTransfer $replacementProductOfferTransfer,
        ItemTransfer $mergedItemTransfer,
        array $originalItemTransfers
    ): void {
        foreach ($originalItemTransfers as $itemTransfer) {
            if (
                !$this->itemMerger->isSameItemTransfer($itemTransfer, $mergedItemTransfer)
                || $itemTransfer->getProductOfferReference() === $replacementProductOfferTransfer->getProductOfferReference()
            ) {
                continue;
            }

            $itemTransfer->setProductOfferReference($replacementProductOfferTransfer->getProductOfferReference());
            $itemTransfer->setGroupKey(null);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $mergedItemTransfer
     * @param \Generated\Shared\Transfer\QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $originalItemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    protected function addFailedItemsToQuoteReplacementResponseTransfer(
        ItemTransfer $mergedItemTransfer,
        QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer,
        array $originalItemTransfers
    ): QuoteReplacementResponseTransfer {
        foreach ($originalItemTransfers as $itemTransfer) {
            if (!$this->itemMerger->isSameItemTransfer($itemTransfer, $mergedItemTransfer)) {
                continue;
            }

            $quoteReplacementResponseTransfer->addFailedReplacementItem($itemTransfer);
            $this->quoteReplacementResponseErrorAdder->addError($quoteReplacementResponseTransfer, $itemTransfer);
        }

        return $quoteReplacementResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isFilteredByIsActive(QuoteTransfer $quoteTransfer): bool
    {
        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()?->getNameOrFail();
        $isOrderAmendment = $quoteProcessFlowName === SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT;

        return !$isOrderAmendment || $this->clickAndCollectExampleConfig->isProductOfferFilteredByIsActiveForOrderAmendment();
    }
}
