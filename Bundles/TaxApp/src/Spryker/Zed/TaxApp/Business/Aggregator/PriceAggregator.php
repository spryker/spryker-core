<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\TaxAppItemTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;
use Generated\Shared\Transfer\TaxAppShipmentTransfer;
use Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpensePriceRetriever;
use Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapper;

class PriceAggregator implements PriceAggregatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function calculatePriceAggregation(
        TaxAppSaleTransfer $taxAppSaleTransfer,
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer {
        $this->calculateTaxAmountFullAggregationAndPriceToPayAggregationForItems($taxAppSaleTransfer, $calculableObjectTransfer);
        $this->calculatePriceToPayAggregationForExpenses($taxAppSaleTransfer, $calculableObjectTransfer);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function calculateTaxAmountFullAggregationAndPriceToPayAggregationForItems(
        TaxAppSaleTransfer $taxAppSaleTransfer,
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer {
        $indexedQuoteItems = $this->getItemsIndexedBySkuAndItemIndex($calculableObjectTransfer->getItems());

        /** @var \Generated\Shared\Transfer\TaxAppItemTransfer $taxAppSaleItem */
        foreach ($taxAppSaleTransfer->getItems() as $taxAppSaleItem) {
            if (!isset($indexedQuoteItems[$taxAppSaleItem->getId()])) {
                continue;
            }

            $indexedQuoteItems[$taxAppSaleItem->getId()] = $this->calculateTaxAmountFullAggregationForItem($indexedQuoteItems[$taxAppSaleItem->getId()], $taxAppSaleItem);
            $indexedQuoteItems[$taxAppSaleItem->getId()] = $this->calculatePriceToPayAggregationForItem($indexedQuoteItems[$taxAppSaleItem->getId()], $calculableObjectTransfer->getPriceModeOrFail());
            $indexedQuoteItems[$taxAppSaleItem->getId()]->setTaxRateAverageAggregation(0);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteItem
     * @param \Generated\Shared\Transfer\TaxAppItemTransfer $taxAppItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function calculateTaxAmountFullAggregationForItem(ItemTransfer $quoteItem, TaxAppItemTransfer $taxAppItemTransfer): ItemTransfer
    {
        $saleItemQuantity = $this->getItemQuantity($taxAppItemTransfer);

        $taxTotal = (int)$taxAppItemTransfer->getTaxTotal();

        if ($taxAppItemTransfer->getRefundedTaxTotal()) {
            $taxTotal = $taxAppItemTransfer->getRefundedTaxTotal();
            $quoteItem->setTaxAmountAfterCancellation($taxAppItemTransfer->getRefundedTaxTotal());
        }

        $quoteItem->setUnitTaxAmount((int)round($taxTotal / $saleItemQuantity));
        $quoteItem->setSumTaxAmount($taxTotal);
        // TaxAmountFullAggregation includes ProductOption taxes which are not supported by TaxApp module.
        $quoteItem->setUnitTaxAmountFullAggregation((int)round($taxTotal / $saleItemQuantity));
        $quoteItem->setSumTaxAmountFullAggregation($taxTotal);

        return $quoteItem;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppItemTransfer $taxAppItemTransfer
     *
     * @return int
     */
    protected function getItemQuantity(TaxAppItemTransfer $taxAppItemTransfer): int
    {
        if (!$taxAppItemTransfer->getShippingWarehouses()->count()) {
            return $taxAppItemTransfer->getQuantityOrFail();
        }

        $quantity = 0;
        foreach ($taxAppItemTransfer->getShippingWarehouses() as $warehouseMapping) {
            $quantity = $quantity + $warehouseMapping->getQuantity();
        }

        return $quantity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function calculatePriceToPayAggregationForItem(ItemTransfer $itemTransfer, string $priceMode): ItemTransfer
    {
        $itemTransfer->requireSumSubtotalAggregation()
            ->requireUnitSubtotalAggregation();

        $itemTransfer->setUnitPriceToPayAggregation(
            $this->calculatePriceToPayAggregation(
                $itemTransfer->getUnitSubtotalAggregationOrFail(),
                $priceMode,
                $itemTransfer->getUnitDiscountAmountAggregation() ?? 0,
                $itemTransfer->getUnitTaxAmountFullAggregation() ?? 0,
            ),
        );

        $itemTransfer->setSumPriceToPayAggregation(
            $this->calculatePriceToPayAggregation(
                $itemTransfer->getSumSubtotalAggregationOrFail(),
                $priceMode,
                $itemTransfer->getSumDiscountAmountFullAggregation() ?? 0,
                $itemTransfer->getSumTaxAmountFullAggregation() ?? 0,
            ),
        );

        return $itemTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemsIndexedBySkuAndItemIndex(ArrayObject $itemTransfers): array
    {
        $indexedItems = [];
        foreach ($itemTransfers as $itemIndex => $itemTransfer) {
            $indexedItems[sprintf('%s_%s', $itemTransfer->getSku(), $itemIndex)] = $itemTransfer;
        }

        return $indexedItems;
    }

    /**
     * @inheritDoc
     */
    protected function calculatePriceToPayAggregationForExpenses(
        TaxAppSaleTransfer $taxAppSaleTransfer,
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer {
        $calculableObjectTransfer = $this->preDefineTaxAmount($calculableObjectTransfer);
        $indexedExpenses = $this->filterShipmentExpenses($calculableObjectTransfer->getExpenses());

        foreach ($taxAppSaleTransfer->getShipments() as $taxAppShipmentTransfer) {
            if (!isset($indexedExpenses[$taxAppShipmentTransfer->getId()])) {
                continue;
            }

            $indexedExpenses[$taxAppShipmentTransfer->getId()] = $this->calculateTaxAmountForExpense($indexedExpenses[$taxAppShipmentTransfer->getId()], $taxAppShipmentTransfer);
            $indexedExpenses[$taxAppShipmentTransfer->getId()] = $this->calculatePriceToPayAggregationForExpense($indexedExpenses[$taxAppShipmentTransfer->getId()], $calculableObjectTransfer->getPriceModeOrFail());
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function preDefineTaxAmount(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getSumTaxAmount() === null) {
                $expenseTransfer->setSumTaxAmount(0);
            }
            if ($expenseTransfer->getUnitTaxAmount() === null) {
                $expenseTransfer->setUnitTaxAmount(0);
            }
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\TaxAppShipmentTransfer $taxAppShipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function calculateTaxAmountForExpense(ExpenseTransfer $expenseTransfer, TaxAppShipmentTransfer $taxAppShipmentTransfer): ExpenseTransfer
    {
        if ($taxAppShipmentTransfer->getRefundedTaxTotal()) {
            $expenseTransfer->setTaxAmountAfterCancellation($taxAppShipmentTransfer->getRefundedTaxTotal());

            return $expenseTransfer;
        }

        $expenseTransfer->setUnitTaxAmount($taxAppShipmentTransfer->getTaxTotal());
        $expenseTransfer->setSumTaxAmount($taxAppShipmentTransfer->getTaxTotal());

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function calculatePriceToPayAggregationForExpense(ExpenseTransfer $expenseTransfer, string $priceMode): ExpenseTransfer
    {
        $expenseTransfer->setUnitPriceToPayAggregation(
            $this->calculatePriceToPayAggregation(
                $expenseTransfer->getUnitPriceOrFail(),
                $priceMode,
                $expenseTransfer->getUnitDiscountAmountAggregation() ?? 0,
                $expenseTransfer->getUnitTaxAmount() ?? 0,
            ),
        );

        $expenseTransfer->setSumPriceToPayAggregation(
            $this->calculatePriceToPayAggregation(
                $expenseTransfer->getSumPriceOrFail(),
                $priceMode,
                $expenseTransfer->getSumDiscountAmountAggregation() ?? 0,
                $expenseTransfer->getSumTaxAmount() ?? 0,
            ),
        );

        return $expenseTransfer;
    }

    /**
     * @param \ArrayObject<string, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ExpenseTransfer>
     */
    protected function filterShipmentExpenses(ArrayObject $expenseTransfers): array
    {
        $indexedExpenses = [];
        foreach ($expenseTransfers as $hash => $expenseTransfer) {
            if ($expenseTransfer->getType() !== TaxAppMapper::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $indexedExpenses[$hash] = $expenseTransfer;
        }

        return $indexedExpenses;
    }

    /**
     * @param int $price
     * @param string $priceMode
     * @param int $discountAmount
     * @param int $taxAmount
     *
     * @return int
     */
    protected function calculatePriceToPayAggregation(int $price, string $priceMode, int $discountAmount = 0, int $taxAmount = 0): int
    {
        if ($priceMode === ItemExpensePriceRetriever::PRICE_MODE_NET) {
            return $price + $taxAmount - $discountAmount;
        }

        return $price - $discountAmount;
    }
}
