<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductSalesOrderAmendment\PriceResolver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface;

class OrderAmendmentPriceResolver implements OrderAmendmentPriceResolverInterface
{
    /**
     * @param \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface $priceProductSalesOrderAmendmentService
     */
    public function __construct(protected PriceProductSalesOrderAmendmentServiceInterface $priceProductSalesOrderAmendmentService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function resolve(
        PriceProductTransfer $priceProductTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductTransfer {
        if (
            !$this->isQuoteInOrderAmendmentProcess($priceProductFilterTransfer)
            || !$priceProductFilterTransfer->getPriceProductResolveConditions()
        ) {
            return $priceProductTransfer;
        }

        $originalSalesOrderItemUnitPrice = $this->findOriginalSalesOrderItemUnitPrice(
            $priceProductFilterTransfer->getQuoteOrFail(),
            $priceProductFilterTransfer,
        );

        if (!$originalSalesOrderItemUnitPrice) {
            return $priceProductTransfer;
        }

        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

        if ($moneyValueTransfer->getNetAmount() !== null) {
            $newNetAmount = $this->priceProductSalesOrderAmendmentService->resolveOriginalSalesOrderItemPrice(
                $moneyValueTransfer->getNetAmountOrFail(),
                $originalSalesOrderItemUnitPrice,
                $priceProductFilterTransfer->getQuote(),
            );

            $moneyValueTransfer->setNetAmount($newNetAmount);
        }

        if ($moneyValueTransfer->getGrossAmount() !== null) {
            $newGrossAmount = $this->priceProductSalesOrderAmendmentService->resolveOriginalSalesOrderItemPrice(
                $moneyValueTransfer->getGrossAmountOrFail(),
                $originalSalesOrderItemUnitPrice,
                $priceProductFilterTransfer->getQuote(),
            );

            $moneyValueTransfer->setGrossAmount($newGrossAmount);
        }

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    protected function isQuoteInOrderAmendmentProcess(PriceProductFilterTransfer $priceProductFilterTransfer): bool
    {
        $quoteTransfer = $priceProductFilterTransfer->getQuote();
        if (!$quoteTransfer || !$quoteTransfer->getAmendmentOrderReference()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return int|null
     */
    protected function findOriginalSalesOrderItemUnitPrice(
        QuoteTransfer $quoteTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): ?int {
        $itemTransfer = (new ItemTransfer())
            ->fromArray($priceProductFilterTransfer->getPriceProductResolveConditionsOrFail()->toArray(), true);

        return $quoteTransfer->getOriginalSalesOrderItemUnitPrices()[$this->priceProductSalesOrderAmendmentService->buildOriginalSalesOrderItemPriceGroupKey($itemTransfer)] ?? null;
    }
}
