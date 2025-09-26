<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;

class SspAssetQuoteItemSetter implements SspAssetQuoteItemSetterInterface
{
    public function __construct(
        protected QuoteFacadeInterface $quoteFacade,
        protected QuoteItemFinderInterface $itemFinder
    ) {
    }

    public function setSspAssetToQuoteItem(SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($sspAssetQuoteItemAttachmentRequestTransfer->getIdQuoteOrFail());
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();

        if (!$quoteTransfer) {
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }

        if (!$quoteTransfer->getCustomer()) {
            $quoteTransfer->setCustomer((new CustomerTransfer())->setCustomerReference($quoteTransfer->getCustomerReferenceOrFail()));
        }

        $itemTransfer = $this->itemFinder->findItem(
            $quoteTransfer,
            $sspAssetQuoteItemAttachmentRequestTransfer->getItemOrFail(),
        );
        if (!$itemTransfer) {
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }

        $this->updateItemWithSspAsset($itemTransfer, $sspAssetQuoteItemAttachmentRequestTransfer->getSspAssetReference());

        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    protected function updateItemWithSspAsset(ItemTransfer $itemTransfer, ?string $sspAssetReference): void
    {
        if (!$sspAssetReference) {
            $itemTransfer->setSspAsset(null);

            return;
        }

        $itemTransfer->setSspAsset(
            (new SspAssetTransfer())
                ->setReference($sspAssetReference),
        );
    }
}
