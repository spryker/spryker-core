<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

 namespace SprykerFeature\Client\SelfServicePortal\Asset\Quote;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Client\Quote\QuoteClientInterface;

class SessionQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_SESSION
     *
     * @var string
     */
    public const STORAGE_STRATEGY_SESSION = 'session';

    public function __construct(
        protected QuoteClientInterface $quoteClient,
        protected QuoteItemFinderInterface $quoteItemFinder
    ) {
    }

    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY_SESSION;
    }

    public function attachSspAssetToQuoteItem(SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        $quoteItemTransfer = $this->findItem($quoteTransfer, $sspAssetQuoteItemAttachmentRequestTransfer->getItemOrFail());

        $quoteResponseTransfer = new QuoteResponseTransfer();

        if (!$quoteItemTransfer) {
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }

        if ($sspAssetQuoteItemAttachmentRequestTransfer->getSspAssetReference()) {
            $quoteItemTransfer->setSspAsset(
                (new SspAssetTransfer())->setReference($sspAssetQuoteItemAttachmentRequestTransfer->getSspAssetReference()),
            );
        }

        if (!$sspAssetQuoteItemAttachmentRequestTransfer->getSspAssetReference()) {
            $quoteItemTransfer->setSspAsset(null);
        }

        $this->quoteClient->setQuote($quoteTransfer);
        $quoteResponseTransfer->setIsSuccessful(true);

        return $quoteResponseTransfer;
    }

    protected function findItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer): ?ItemTransfer
    {
        return $this->quoteItemFinder->findItem($quoteTransfer, $itemTransfer);
    }
}
