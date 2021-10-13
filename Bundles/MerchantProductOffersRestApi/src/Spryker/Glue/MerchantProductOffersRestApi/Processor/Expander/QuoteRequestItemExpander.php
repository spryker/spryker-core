<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander;

class QuoteRequestItemExpander implements QuoteRequestItemExpanderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    public function expandRestQuoteRequestItemWithMerchantProductOffers(
        array $restQuoteRequestsAttributesTransfers,
        array $quoteRequestTransfers,
        string $localeName
    ): array {
        $indexedRestQuoteRequestsAttributesTransfers = $this->getRestQuoteRequestsAttributesTransfersIndexedByQuoteRequestReference($restQuoteRequestsAttributesTransfers);
        foreach ($quoteRequestTransfers as $quoteRequestTransfer) {
            if (!isset($indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()])) {
                continue;
            }

            $restQuoteRequestsAttributesTransfer = $indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()];
            if (
                $quoteRequestTransfer->getLatestVersion() !== null
                && $quoteRequestTransfer->getLatestVersion()->getQuote() !== null
                && $restQuoteRequestsAttributesTransfer->getShownVersion() !== null
                && $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart() !== null
            ) {
                continue;
            }

            $itemTransfers = $quoteRequestTransfer->getLatestVersion()->getQuote()->getItems();
            $restQuoteRequestItemsByGroupKey = $this->getRestQuoteRequestItemsIndexedByGroupKey(($restQuoteRequestsAttributesTransfer->getShownVersion()->getCart()->getItems())->getArrayCopy());

            foreach ($itemTransfers as $itemTransfer) {
                if ($itemTransfer->getMerchantReference() === null) {
                    continue;
                }

                $itemGroupKey = $itemTransfer->getGroupKey();
                if (!isset($restQuoteRequestItemsByGroupKey[$itemGroupKey])) {
                    continue;
                }
                $restQuoteRequestItemTransfer = $restQuoteRequestItemsByGroupKey[$itemGroupKey];

                $restQuoteRequestItemTransfer
                    ->setMerchantReference($itemTransfer->getMerchantReference())
                    ->setProductOfferReference($itemTransfer->getProductOfferReference());
            }
        }

        return $restQuoteRequestsAttributesTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestItemTransfer> $restQuoteRequestItemTransfers
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestItemTransfer>
     */
    protected function getRestQuoteRequestItemsIndexedByGroupKey(array $restQuoteRequestItemTransfers): array
    {
        $restQuoteRequestItemTransfersByGroupKey = [];
        foreach ($restQuoteRequestItemTransfers as $restQuoteRequestItemTransfer) {
            $restQuoteRequestItemTransfersByGroupKey[$restQuoteRequestItemTransfer->getGroupKey()] = $restQuoteRequestItemTransfer;
        }

        return $restQuoteRequestItemTransfersByGroupKey;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    protected function getRestQuoteRequestsAttributesTransfersIndexedByQuoteRequestReference(array $restQuoteRequestsAttributesTransfers): array
    {
        $indexedRestQuoteRequestsAttributesTransfers = [];
        foreach ($restQuoteRequestsAttributesTransfers as $restQuoteRequestsAttributesTransfer) {
            $indexedRestQuoteRequestsAttributesTransfers[$restQuoteRequestsAttributesTransfer->getQuoteRequestReference()] = $restQuoteRequestsAttributesTransfer;
        }

        return $indexedRestQuoteRequestsAttributesTransfers;
    }
}
