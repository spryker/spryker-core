<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DiscountsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsCartTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsDiscountsTransfer;

class DiscountMapper implements DiscountMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer[] $quoteRequestTransfers
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[] $restQuoteRequestsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[]
     */
    public function mapDiscountTransfersToRestQuoteRequestsAttributesTransfers(
        array $quoteRequestTransfers,
        array $restQuoteRequestsAttributesTransfers
    ): array {
        $indexedRestQuoteRequestsAttributesTransfers = $this->getRestQuoteRequestsAttributesTransfersIndexedByQuoteRequestReference($restQuoteRequestsAttributesTransfers);
        foreach ($quoteRequestTransfers as $quoteRequestTransfer) {
            if (
                !isset($indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()]) ||
                $quoteRequestTransfer->getLatestVersion() === null ||
                $quoteRequestTransfer->getLatestVersion()->getQuote() === null
            ) {
                continue;
            }

            $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();
            $restQuoteRequestsAttributesTransfer = $indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()];
            if (
                $restQuoteRequestsAttributesTransfer->getShownVersion() === null ||
                $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart() === null
            ) {
                continue;
            }
            $restQuoteRequestsCartTransfer = $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart();
            foreach ($quoteTransfer->getVoucherDiscounts() as $voucherDiscount) {
                $restQuoteRequestsCartTransfer = $this->addDiscountToRestCartTransfer(
                    $voucherDiscount,
                    $restQuoteRequestsCartTransfer
                );
            }
            foreach ($quoteTransfer->getCartRuleDiscounts() as $discountTransfer) {
                $restQuoteRequestsCartTransfer = $this->addDiscountToRestCartTransfer(
                    $discountTransfer,
                    $restQuoteRequestsCartTransfer
                );
            }
        }

        return $restQuoteRequestsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restQuoteRequestsCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function addDiscountToRestCartTransfer(
        DiscountTransfer $discountTransfer,
        RestQuoteRequestsCartTransfer $restQuoteRequestsCartTransfer
    ): RestQuoteRequestsCartTransfer {
        $restDiscountTransfer = new RestQuoteRequestsDiscountsTransfer();
        $restDiscountTransfer->fromArray($discountTransfer->toArray(), true);
        $restDiscountTransfer->setCode($discountTransfer->getVoucherCode());
        $restQuoteRequestsCartTransfer->addDiscount($restDiscountTransfer);

        return $restQuoteRequestsCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[] $restQuoteRequestsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[]
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
