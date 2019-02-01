<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface;

/**
 * @method \Spryker\Client\SharedCart\SharedCartFactory getFactory()
 */
class SharedCartsUpdateQuoteUpdatePlugin extends AbstractPlugin implements QuoteUpdatePluginInterface
{
    /**
     * Specification:
     * - Adds shared cart list to multi cart collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function processResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        if ($quoteResponseTransfer->getSharedCustomerQuotes() && count($quoteResponseTransfer->getSharedCustomerQuotes()->getQuotes())) {
            $multiCartClient = $this->getFactory()->getMultiCartClient();
            $customerQuoteCollectionTransfer = $multiCartClient->getQuoteCollection();
            $sharedQuoteCollectionTransfer = $quoteResponseTransfer->getSharedCustomerQuotes();
            $customerQuoteCollectionTransfer = $this->mergeQuoteCollections($customerQuoteCollectionTransfer, $sharedQuoteCollectionTransfer);
            $multiCartClient->setQuoteCollection(
                $this->sortQuoteCollectionByName($customerQuoteCollectionTransfer)
            );
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $customerQuoteCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $sharedQuoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function mergeQuoteCollections(
        QuoteCollectionTransfer $customerQuoteCollectionTransfer,
        QuoteCollectionTransfer $sharedQuoteCollectionTransfer
    ): QuoteCollectionTransfer {
        $mergedQuoteCollectionTransfer = $this->removeSharedQuotes($customerQuoteCollectionTransfer);

        foreach ($sharedQuoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($this->isQuoteAlreadyAdded($mergedQuoteCollectionTransfer, $quoteTransfer)) {
                continue;
            }

            $mergedQuoteCollectionTransfer->addQuote($quoteTransfer);
        }

        return $mergedQuoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteAlreadyAdded(QuoteCollectionTransfer $quoteCollectionTransfer, QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteCollectionTransfer->getQuotes() as $addedQuoteTransfer) {
            if ($addedQuoteTransfer->getIdQuote() === $quoteTransfer->getIdQuote()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function removeSharedQuotes(QuoteCollectionTransfer $quoteCollectionTransfer): QuoteCollectionTransfer
    {
        $quoteTransfers = $quoteCollectionTransfer->getQuotes()->getArrayCopy();

        foreach ($quoteTransfers as $key => $quoteTransfer) {
            if ($quoteTransfer->getCustomerReference() !== $quoteTransfer->getCustomer()->getCustomerReference()) {
                unset($quoteTransfers[$key]);
            }
        }

        $quoteCollectionTransfer->setQuotes(new ArrayObject($quoteTransfers));

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function sortQuoteCollectionByName(QuoteCollectionTransfer $quoteCollectionTransfer): QuoteCollectionTransfer
    {
        $quoteTransferList = (array)$quoteCollectionTransfer->getQuotes();
        usort($quoteTransferList, [$this, 'compareQuotes']);
        $quoteCollectionTransfer->setQuotes(
            new ArrayObject($quoteTransferList)
        );

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransferA
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransferB
     *
     * @return int
     */
    protected function compareQuotes(QuoteTransfer $quoteTransferA, QuoteTransfer $quoteTransferB): int
    {
        return $quoteTransferA->getName() <=> $quoteTransferB->getName();
    }
}
