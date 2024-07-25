<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Writer;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Reader\MerchantOrderReaderInterface;
use Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Dependency\Facade\MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface;

class MerchantOrderWriter implements MerchantOrderWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Dependency\Facade\MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface
     */
    protected MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Reader\MerchantOrderReaderInterface
     */
    protected MerchantOrderReaderInterface $merchantOrderReader;

    /**
     * @param \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Dependency\Facade\MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     * @param \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Reader\MerchantOrderReaderInterface $merchantOrderReader
     */
    public function __construct(
        MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade,
        MerchantOrderReaderInterface $merchantOrderReader
    ) {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
        $this->merchantOrderReader = $merchantOrderReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return void
     */
    public function saveMerchantCommissionToMerchantOrderTotals(MerchantOrderTransfer $merchantOrderTransfer): void
    {
        $persistedMerchantOrderTransfer = $this->merchantOrderReader->findMerchantOrderByIdMerchantOrder(
            $merchantOrderTransfer->getIdMerchantOrderOrFail(),
        );
        if (!$persistedMerchantOrderTransfer) {
            return;
        }

        $this->updateMerchantOrderTotals($merchantOrderTransfer, $persistedMerchantOrderTransfer->getTotalsOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    public function updateMerchantCommissionToMerchantOrderTotals(OrderTransfer $orderTransfer, array $itemTransfers): void
    {
        $itemTransfersGroupedByMerchantReference = $this->getItemTransfersGroupedByMerchantReference($itemTransfers);
        foreach ($itemTransfersGroupedByMerchantReference as $merchantReference => $itemTransfers) {
            $merchantOrderTransfer = $this->merchantOrderReader->findMerchantOrderByIdSalesOrderAndMerchantReference(
                $orderTransfer->getIdSalesOrderOrFail(),
                $merchantReference,
            );
            if (!$merchantOrderTransfer) {
                continue;
            }

            $this->updateMerchantOrderTotals($merchantOrderTransfer, $merchantOrderTransfer->getTotalsOrFail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return void
     */
    protected function updateMerchantOrderTotals(
        MerchantOrderTransfer $merchantOrderTransfer,
        TotalsTransfer $totalsTransfer
    ): void {
        [$merchantCommissionTotal, $merchantCommissionRefundedTotal] = $this->extractMerchantCommissionTotalsFromOrderItems(
            $merchantOrderTransfer,
        );

        $updatedTotalsTransfer = (new TotalsTransfer())
            ->fromArray($totalsTransfer->toArray(), true)
            ->setMerchantCommissionTotal($merchantCommissionTotal)
            ->setMerchantCommissionRefundedTotal($merchantCommissionRefundedTotal);

        $orderTransfer = (new OrderTransfer())
            ->addMerchantReference($merchantOrderTransfer->getMerchantReferenceOrFail())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrderOrFail())
            ->setTotals($updatedTotalsTransfer);

        $this->merchantSalesOrderFacade->updateMerchantOrderTotals($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return list<int>
     */
    protected function extractMerchantCommissionTotalsFromOrderItems(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $merchantCommissionTotal = 0;
        $merchantCommissionRefundedTotal = 0;

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $itemTransfer = $merchantOrderItemTransfer->getOrderItemOrFail();

            $merchantCommissionTotal += $itemTransfer->getMerchantCommissionAmountFullAggregation() ?? 0;
            $merchantCommissionRefundedTotal += $itemTransfer->getMerchantCommissionRefundedAmount() ?? 0;
        }

        return [$merchantCommissionTotal, $merchantCommissionRefundedTotal];
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer>>
     */
    protected function getItemTransfersGroupedByMerchantReference(array $itemTransfers): array
    {
        $groupedItemTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getMerchantReference()) {
                $groupedItemTransfers[$itemTransfer->getMerchantReferenceOrFail()][] = $itemTransfer;
            }
        }

        return $groupedItemTransfers;
    }
}
