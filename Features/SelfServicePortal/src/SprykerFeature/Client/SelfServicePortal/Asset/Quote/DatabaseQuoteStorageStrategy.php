<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Asset\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;
use Spryker\Client\Quote\QuoteClientInterface;
use SprykerFeature\Client\SelfServicePortal\Zed\SelfServicePortalStubInterface;

class DatabaseQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     *
     * @var string
     */
    public const STORAGE_STRATEGY_DATABASE = 'database';

    public function __construct(
        protected QuoteClientInterface $quoteClient,
        protected SelfServicePortalStubInterface $selfServicePortalStub
    ) {
    }

    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY_DATABASE;
    }

    public function attachSspAssetToQuoteItem(SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if (!$quoteTransfer->getCustomer()) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }

        $sspAssetQuoteItemAttachmentRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());

        $quoteResponseTransfer = $this->selfServicePortalStub->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteTransfer->fromArray($quoteResponseTransfer->getQuoteTransferOrFail()->modifiedToArray());
            $this->quoteClient->setQuote($quoteTransfer);
        }

        return $quoteResponseTransfer;
    }
}
