<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Writer;

use Generated\Shared\Transfer\MerchantOrderItemTransfer;
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
        [$merchantCommissionTotal, $merchantCommissionRefundedTotal] = $this->extractMerchantCommissionTotalsFromOrderItems(
            $merchantOrderTransfer,
        );

        $persistedMerchantOrderTransfer = $this->merchantOrderReader->findMerchantOrder($merchantOrderTransfer);
        if (!$persistedMerchantOrderTransfer) {
            return;
        }

        $totalsTransfer = (new TotalsTransfer())
            ->fromArray($persistedMerchantOrderTransfer->getTotalsOrFail()->toArray(), true)
            ->setMerchantCommissionTotal($merchantCommissionTotal)
            ->setMerchantCommissionRefundedTotal($merchantCommissionRefundedTotal);

        $orderTransfer = (new OrderTransfer())
            ->addMerchantReference($merchantOrderTransfer->getMerchantReferenceOrFail())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrderOrFail())
            ->setTotals($totalsTransfer);

        $this->merchantSalesOrderFacade->updateMerchantOrderTotals($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    public function updateMerchantCommissionToMerchantOrderTotals(OrderTransfer $orderTransfer, array $itemTransfers): void
    {
        $merchantOrderTransfer = (new MerchantOrderTransfer())
            ->setIdOrder($orderTransfer->getIdSalesOrderOrFail());

        foreach ($itemTransfers as $itemTransfer) {
            $merchantReference = $itemTransfer->getMerchantReference();
            if (!$merchantReference) {
                return;
            }

            if ($merchantOrderTransfer->getMerchantReference() && $merchantOrderTransfer->getMerchantReference() !== $merchantReference) {
                return;
            }

            $merchantOrderTransfer
                ->setMerchantReference($merchantReference)
                ->addMerchantOrderItem((new MerchantOrderItemTransfer())->setOrderItem($itemTransfer));
        }

        $this->updateMerchantCommissionTotals($merchantOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return void
     */
    protected function updateMerchantCommissionTotals(MerchantOrderTransfer $merchantOrderTransfer): void
    {
        $persistedMerchantOrderTransfer = $this->merchantOrderReader->findMerchantOrder($merchantOrderTransfer);
        if (!$persistedMerchantOrderTransfer) {
            return;
        }

        $totalsTransfer = $persistedMerchantOrderTransfer->getTotalsOrFail();
        [$merchantCommissionTotal, $merchantCommissionRefundedTotal] = $this->extractMerchantCommissionTotalsFromOrderItems(
            $merchantOrderTransfer,
        );

        $totalsTransfer = (new TotalsTransfer())
            ->fromArray($persistedMerchantOrderTransfer->getTotalsOrFail()->toArray(), true)
            ->setMerchantCommissionTotal($totalsTransfer->getMerchantCommissionTotal() - $merchantCommissionRefundedTotal)
            ->setMerchantCommissionRefundedTotal($totalsTransfer->getMerchantCommissionRefundedTotal() + $merchantCommissionRefundedTotal);

        $orderTransfer = (new OrderTransfer())
            ->addMerchantReference($merchantOrderTransfer->getMerchantReferenceOrFail())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrderOrFail())
            ->setTotals($totalsTransfer);

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
}
