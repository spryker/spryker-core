<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesMerchantCommission\Business\Collector\MerchantCommissionCollectorInterface;
use Spryker\Zed\SalesMerchantCommission\Business\Reader\SalesMerchantCommissionReaderInterface;

class MerchantCommissionCalculator implements MerchantCommissionCalculatorInterface
{
    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Business\Collector\MerchantCommissionCollectorInterface
     */
    protected MerchantCommissionCollectorInterface $merchantCommissionCollector;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Business\Reader\SalesMerchantCommissionReaderInterface
     */
    protected SalesMerchantCommissionReaderInterface $salesMerchantCommissionReader;

    /**
     * @param \Spryker\Zed\SalesMerchantCommission\Business\Collector\MerchantCommissionCollectorInterface $merchantCommissionCollector
     * @param \Spryker\Zed\SalesMerchantCommission\Business\Reader\SalesMerchantCommissionReaderInterface $salesMerchantCommissionReader
     */
    public function __construct(
        MerchantCommissionCollectorInterface $merchantCommissionCollector,
        SalesMerchantCommissionReaderInterface $salesMerchantCommissionReader
    ) {
        $this->merchantCommissionCollector = $merchantCommissionCollector;
        $this->salesMerchantCommissionReader = $salesMerchantCommissionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateMerchantCommissions(
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer {
        if (!$calculableObjectTransfer->getOriginalOrder() || !$calculableObjectTransfer->getOriginalOrderOrFail()->getIdSalesOrder()) {
            return $calculableObjectTransfer;
        }

        $idSalesOrder = $calculableObjectTransfer->getOriginalOrderOrFail()->getIdSalesOrderOrFail();
        $salesMerchantCommissionCollectionTransfer = $this->salesMerchantCommissionReader
            ->getSalesMerchantCommissionsByIdSalesOrder($idSalesOrder);

        $itemSalesMerchantCommissions = $this->merchantCommissionCollector
            ->collectItemSalesMerchantCommissions($salesMerchantCommissionCollectionTransfer);
        $orderSalesMerchantCommissions = $this->merchantCommissionCollector
            ->collectOrderSalesMerchantCommissions($salesMerchantCommissionCollectionTransfer);

        $calculableObjectTransfer = $this->recalculateItemMerchantCommissions($calculableObjectTransfer, $itemSalesMerchantCommissions);

        return $this->recalculateTotalsMerchantCommissions(
            $calculableObjectTransfer,
            $orderSalesMerchantCommissions,
            $itemSalesMerchantCommissions,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param array<int, list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>> $itemSalesMerchantCommissions
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function recalculateItemMerchantCommissions(
        CalculableObjectTransfer $calculableObjectTransfer,
        array $itemSalesMerchantCommissions
    ): CalculableObjectTransfer {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $this->recalculateItemMerchantCommission($itemTransfer, $itemSalesMerchantCommissions);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer> $orderSalesMerchantCommissions
     * @param array<int, list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>> $itemSalesMerchantCommissions
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function recalculateTotalsMerchantCommissions(
        CalculableObjectTransfer $calculableObjectTransfer,
        array $orderSalesMerchantCommissions,
        array $itemSalesMerchantCommissions
    ): CalculableObjectTransfer {
        $merchantCommissionAmount = 0;
        $merchantCommissionRefundedAmount = 0;

        foreach ($orderSalesMerchantCommissions as $salesMerchantCommissionTransfer) {
            $merchantCommissionAmount += $salesMerchantCommissionTransfer->getAmountOrFail();
            $merchantCommissionRefundedAmount += $salesMerchantCommissionTransfer->getRefundedAmountOrFail();
        }

        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $salesMerchantCommissionTransfers = $itemSalesMerchantCommissions[$itemTransfer->getIdSalesOrderItemOrFail()] ?? [];

            foreach ($salesMerchantCommissionTransfers as $salesMerchantCommissionTransfer) {
                $merchantCommissionAmount += $salesMerchantCommissionTransfer->getAmountOrFail();
                $merchantCommissionRefundedAmount += $salesMerchantCommissionTransfer->getRefundedAmountOrFail();
            }
        }

        $calculableObjectTransfer->getTotalsOrFail()
            ->setMerchantCommissionTotal($merchantCommissionAmount - $merchantCommissionRefundedAmount)
            ->setMerchantCommissionRefundedTotal($merchantCommissionRefundedAmount);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<int, list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>> $itemSalesMerchantCommissions
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function recalculateItemMerchantCommission(
        ItemTransfer $itemTransfer,
        array $itemSalesMerchantCommissions
    ): ItemTransfer {
        $merchantCommissionAmount = 0;
        $merchantCommissionRefundedAmount = 0;
        $salesMerchantCommissionTransfers = $itemSalesMerchantCommissions[$itemTransfer->getIdSalesOrderItemOrFail()] ?? [];

        foreach ($salesMerchantCommissionTransfers as $salesMerchantCommissionTransfer) {
            $merchantCommissionAmount += $salesMerchantCommissionTransfer->getAmountOrFail();
            $merchantCommissionRefundedAmount += $salesMerchantCommissionTransfer->getRefundedAmountOrFail();
        }

        return $itemTransfer
            ->setMerchantCommissionAmountAggregation($merchantCommissionAmount - $merchantCommissionRefundedAmount)
            ->setMerchantCommissionAmountFullAggregation($merchantCommissionAmount - $merchantCommissionRefundedAmount)
            ->setMerchantCommissionRefundedAmount($merchantCommissionRefundedAmount);
    }
}
