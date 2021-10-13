<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestItemTransfer;
use Generated\Shared\Transfer\RestQuoteRequestSalesUnitTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;

class QuoteRequestItemExpander implements QuoteRequestItemExpanderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    public function expandRestQuoteRequestItemWithSalesUnit(
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
            if (!$this->areTransfersValid($quoteRequestTransfer, $restQuoteRequestsAttributesTransfer)) {
                continue;
            }

            $itemTransfers = $quoteRequestTransfer->getLatestVersion()->getQuote()->getItems();
            $restQuoteRequestItemsByGroupKey = $this->getRestQuoteRequestItemsIndexedByGroupKey(($restQuoteRequestsAttributesTransfer->getShownVersion()->getCart()->getItems())->getArrayCopy());

            foreach ($itemTransfers as $itemTransfer) {
                $itemGroupKey = $itemTransfer->getGroupKey();
                if (!isset($restQuoteRequestItemsByGroupKey[$itemGroupKey])) {
                    continue;
                }
                $this->setSalesUnitForRestQuoteRequestItemTransfer($restQuoteRequestItemsByGroupKey[$itemGroupKey], $itemTransfer);
            }
        }

        return $restQuoteRequestsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     *
     * @return bool
     */
    protected function areTransfersValid(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
    ): bool {
        return $quoteRequestTransfer->getLatestVersion() !== null
            && $quoteRequestTransfer->getLatestVersion()->getQuote() !== null
            && $restQuoteRequestsAttributesTransfer->getShownVersion() !== null
            && $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart() !== null;
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

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestItemTransfer $restQuoteRequestItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setSalesUnitForRestQuoteRequestItemTransfer(
        RestQuoteRequestItemTransfer $restQuoteRequestItemTransfer,
        ItemTransfer $itemTransfer
    ): void {
        $productMeasurementSalesUnitTransfer = $itemTransfer->getAmountSalesUnit();
        if ($productMeasurementSalesUnitTransfer !== null) {
            $restCartItemsSalesUnitAttributesTransfer = (new RestQuoteRequestSalesUnitTransfer())
                ->setId($productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit())
                ->setAmount($itemTransfer->getAmount());

            $restQuoteRequestItemTransfer->setSalesUnit($restCartItemsSalesUnitAttributesTransfer);
        }
    }
}
