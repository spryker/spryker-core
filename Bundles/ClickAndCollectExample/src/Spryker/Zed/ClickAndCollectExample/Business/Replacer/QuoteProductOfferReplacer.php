<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Replacer;

use ArrayObject;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteProductOfferReplacer implements QuoteProductOfferReplacerInterface
{
    /**
     * @var list<\Spryker\Zed\ClickAndCollectExample\Business\Replacer\ItemProductOfferReplacerInterface>
     */
    protected array $quoteItemReplacers;

    /**
     * @param list<\Spryker\Zed\ClickAndCollectExample\Business\Replacer\ItemProductOfferReplacerInterface> $quoteItemReplacers
     */
    public function __construct(array $quoteItemReplacers)
    {
        $this->quoteItemReplacers = $quoteItemReplacers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function replaceQuoteItemProductOffers(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer
    {
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())->setQuote($quoteTransfer);

        $combinedQuoteErrorTransfers = [];
        $combinedFailedReplacementItems = [];
        foreach ($this->quoteItemReplacers as $quoteItemReplacer) {
            $quoteReplacementResponseTransfer = $quoteItemReplacer->replaceQuoteItemProductOffers($quoteTransfer);
            $combinedQuoteErrorTransfers[] = $quoteReplacementResponseTransfer->getErrors()->getArrayCopy();
            $combinedFailedReplacementItems[] = $quoteReplacementResponseTransfer->getFailedReplacementItems()->getArrayCopy();
        }

        if (count($combinedQuoteErrorTransfers) !== 0) {
            $quoteReplacementResponseTransfer
                ->setErrors($this->mergeQuoteErrors($combinedQuoteErrorTransfers));
        }

        if (count($combinedFailedReplacementItems) !== 0) {
            $quoteReplacementResponseTransfer
                ->setFailedReplacementItems($this->mergeFailedReplacementItems($combinedFailedReplacementItems));
        }

        return $quoteReplacementResponseTransfer;
    }

    /**
     * @param list<list<\Generated\Shared\Transfer\QuoteErrorTransfer>> $combinedQuoteErrorTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\QuoteErrorTransfer>
     */
    protected function mergeQuoteErrors(
        array $combinedQuoteErrorTransfers
    ): ArrayObject {
        $mergedQuoteErrorTransfers = array_merge(...$combinedQuoteErrorTransfers);

        return new ArrayObject($mergedQuoteErrorTransfers);
    }

    /**
     * @param list<list<\Generated\Shared\Transfer\ItemTransfer>> $combinedFailedReplacementItems
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function mergeFailedReplacementItems(
        array $combinedFailedReplacementItems
    ): ArrayObject {
        $mergedFailedReplacementItemTransfers = array_merge(...$combinedFailedReplacementItems);

        return new ArrayObject($mergedFailedReplacementItemTransfers);
    }
}
