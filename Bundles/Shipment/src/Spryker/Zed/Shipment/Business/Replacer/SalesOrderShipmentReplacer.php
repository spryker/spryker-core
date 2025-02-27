<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Replacer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class SalesOrderShipmentReplacer implements SalesOrderShipmentReplacerInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $shipmentEntityManager
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface $multiShipmentOrderSaver
     */
    public function __construct(
        protected ShipmentEntityManagerInterface $shipmentEntityManager,
        protected ShipmentRepositoryInterface $shipmentRepository,
        protected ShipmentToSalesFacadeInterface $salesFacade,
        protected MultiShipmentOrderSaverInterface $multiShipmentOrderSaver
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function replaceSalesOrderShipment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $saveOrderTransfer->requireIdSalesOrder();

        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer, $saveOrderTransfer): void {
            $this->executeReplaceSalesOrderShipmentTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function executeReplaceSalesOrderShipmentTransaction(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $this->unsetFkSalesShipmentForSalesOrderItems($saveOrderTransfer);

        $this->shipmentEntityManager->deleteSalesShipmentsByIdSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail());

        $this->deleteSalesOrderShipmentExpenses($saveOrderTransfer);

        $this->multiShipmentOrderSaver->saveSalesOrderShipment($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function unsetFkSalesShipmentForSalesOrderItems(SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderItemIds = $this->shipmentRepository->getItemIdsGroupedByShipmentIds(
            (new OrderTransfer())->setIdSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail()),
        );
        $salesOrderItemIds = $this->extractSalesOrderItemIds($salesOrderItemIds);

        $itemTransfers = [];
        foreach ($salesOrderItemIds as $idSalesOrderItem) {
            $itemTransfers[] = (new ItemTransfer())->setIdSalesOrderItem($idSalesOrderItem);
        }

        $this->shipmentEntityManager->updateFkSalesShipmentForSalesOrderItems(
            $itemTransfers,
            null,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function deleteSalesOrderShipmentExpenses(SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail())
            ->addType(ShipmentConfig::SHIPMENT_EXPENSE_TYPE);
        $this->salesFacade->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);
    }

    /**
     * @param array<int|null, list<int>> $salesOrderItemIds
     *
     * @return list<int>
     */
    protected function extractSalesOrderItemIds(array $salesOrderItemIds): array
    {
        $extractedSalesOrderItemIds = [];
        foreach ($salesOrderItemIds as $key => $values) {
            if ($key) {
                $extractedSalesOrderItemIds = array_merge($extractedSalesOrderItemIds, $values);
            }
        }

        return $extractedSalesOrderItemIds;
    }
}
