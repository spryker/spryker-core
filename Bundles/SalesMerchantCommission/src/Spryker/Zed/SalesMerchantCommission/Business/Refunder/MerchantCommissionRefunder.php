<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Refunder;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesMerchantCommission\Business\Reader\SalesMerchantCommissionReaderInterface;
use Spryker\Zed\SalesMerchantCommission\Business\Updater\SalesMerchantCommissionUpdaterInterface;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToCalculationFacadeInterface;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface;

class MerchantCommissionRefunder implements MerchantCommissionRefunderInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Business\Reader\SalesMerchantCommissionReaderInterface
     */
    protected SalesMerchantCommissionReaderInterface $salesMerchantCommissionReader;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Business\Updater\SalesMerchantCommissionUpdaterInterface
     */
    protected SalesMerchantCommissionUpdaterInterface $salesMerchantCommissionUpdater;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToCalculationFacadeInterface
     */
    protected SalesMerchantCommissionToCalculationFacadeInterface $calculationFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface
     */
    protected SalesMerchantCommissionToSalesFacadeInterface $salesFacade;

    /**
     * @var list<\Spryker\Zed\SalesMerchantCommissionExtension\Dependency\Plugin\PostRefundMerchantCommissionPluginInterface>
     */
    protected array $postRefundMerchantCommissionPlugins;

    /**
     * @param \Spryker\Zed\SalesMerchantCommission\Business\Reader\SalesMerchantCommissionReaderInterface $salesMerchantCommissionReader
     * @param \Spryker\Zed\SalesMerchantCommission\Business\Updater\SalesMerchantCommissionUpdaterInterface $salesMerchantCommissionUpdater
     * @param \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface $salesFacade
     * @param list<\Spryker\Zed\SalesMerchantCommissionExtension\Dependency\Plugin\PostRefundMerchantCommissionPluginInterface> $postRefundMerchantCommissionPlugins
     */
    public function __construct(
        SalesMerchantCommissionReaderInterface $salesMerchantCommissionReader,
        SalesMerchantCommissionUpdaterInterface $salesMerchantCommissionUpdater,
        SalesMerchantCommissionToCalculationFacadeInterface $calculationFacade,
        SalesMerchantCommissionToSalesFacadeInterface $salesFacade,
        array $postRefundMerchantCommissionPlugins
    ) {
        $this->salesMerchantCommissionReader = $salesMerchantCommissionReader;
        $this->salesMerchantCommissionUpdater = $salesMerchantCommissionUpdater;
        $this->calculationFacade = $calculationFacade;
        $this->salesFacade = $salesFacade;
        $this->postRefundMerchantCommissionPlugins = $postRefundMerchantCommissionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function refundMerchantCommissions(OrderTransfer $orderTransfer, array $itemTransfers): OrderTransfer
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        $salesMerchantCommissionTransfers = $this->salesMerchantCommissionReader
            ->getSalesMerchantCommissionsBySalesOrderItemIds($salesOrderItemIds);

        $refundableSalesMerchantCommissions = $this->extractRefundableSalesMerchantCommissions(
            $salesOrderItemIds,
            $salesMerchantCommissionTransfers,
        );

        foreach ($refundableSalesMerchantCommissions as $salesMerchantCommissionTransfer) {
            $salesMerchantCommissionTransfer->setRefundedAmount($salesMerchantCommissionTransfer->getAmount());
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($orderTransfer, $itemTransfers, $salesMerchantCommissionTransfers) {
            return $this->executeRefundMerchantCommissionsTransaction($orderTransfer, $itemTransfers, $salesMerchantCommissionTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer> $refundableSalesMerchantCommissions
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeRefundMerchantCommissionsTransaction(
        OrderTransfer $orderTransfer,
        array $itemTransfers,
        array $refundableSalesMerchantCommissions
    ): OrderTransfer {
        $this->salesMerchantCommissionUpdater->updateSalesMerchantCommissions($refundableSalesMerchantCommissions);

        $orderTransfer = $this->calculationFacade->recalculateOrder($orderTransfer);
        $this->salesFacade->updateOrder($orderTransfer, $orderTransfer->getIdSalesOrderOrFail());

        $this->executePostRefundMerchantCommissionPlugins(
            $orderTransfer,
            $this->expandItemsAfterOrderRecalculation($itemTransfers, $orderTransfer),
        );

        return $orderTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function expandItemsAfterOrderRecalculation(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        $expandedOrderItems = $this->getItemTransfersIndexedByIdSalesOrderItem($orderTransfer);
        foreach ($itemTransfers as $itemTransfer) {
            $expandedOrderItem = $expandedOrderItems[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;

            if ($expandedOrderItem) {
                $itemTransfer->fromArray($expandedOrderItem->toArray(), true);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByIdSalesOrderItem(OrderTransfer $orderTransfer): array
    {
        $itemTransfers = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfers[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $itemTransfers;
    }

    /**
     * @param list<int> $salesOrderItemIds
     * @param list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer> $salesMerchantCommissionTransfers
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    protected function extractRefundableSalesMerchantCommissions(
        array $salesOrderItemIds,
        array $salesMerchantCommissionTransfers
    ): array {
        $refundableSalesMerchantCommissions = [];
        foreach ($salesMerchantCommissionTransfers as $salesMerchantCommissionTransfer) {
            if (in_array($salesMerchantCommissionTransfer->getIdSalesOrderItemOrFail(), $salesOrderItemIds, true)) {
                $refundableSalesMerchantCommissions[] = $salesMerchantCommissionTransfer;
            }
        }

        return $refundableSalesMerchantCommissions;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<int>
     */
    protected function extractSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];
        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    protected function executePostRefundMerchantCommissionPlugins(OrderTransfer $orderTransfer, array $itemTransfers): void
    {
        foreach ($this->postRefundMerchantCommissionPlugins as $postRefundMerchantCommissionPlugin) {
            $postRefundMerchantCommissionPlugin->execute($orderTransfer, $itemTransfers);
        }
    }
}
