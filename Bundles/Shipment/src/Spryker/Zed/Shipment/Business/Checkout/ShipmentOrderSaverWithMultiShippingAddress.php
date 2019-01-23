<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Checkout;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

class ShipmentOrderSaverWithMultiShippingAddress implements ShipmentOrderSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentEntityManagerInterface $entityManager,
        ShipmentToSalesFacadeInterface $salesFacade,
        ShipmentServiceInterface $shipmentService
    ) {
        $this->entityManager = $entityManager;
        $this->salesFacade = $salesFacade;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderShipment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        /**
         * @deprecated Remove after multiple shipment will be released.
         */
        $quoteTransfer = $this->adaptQuoteDataBCForMultiShipment($quoteTransfer);

        $this->assertShipmentRequirements($quoteTransfer);

        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $saveOrderTransfer) {
            $this->saveOrderShipmentTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @deprecated Remove after multiple shipment will be released.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function adaptQuoteDataBCForMultiShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() !== null) {
                return $quoteTransfer;
            }
            break;
        }

        $shippingAddress = $quoteTransfer->getShippingAddress();
        if ($shippingAddress === null) {
            return $quoteTransfer;
        }

        $shipmentExpenseTransfer = null;
        foreach ($quoteTransfer->getExpenses() as $key => $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentExpenseTransfer = $expenseTransfer;
            break;
        }

        $quoteShipment = $quoteTransfer->getShipment();
        if ($quoteShipment === null && $shipmentExpenseTransfer === null) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() !== null
                && $itemTransfer->getShipment()->getExpense() !== null
                && $itemTransfer->getShipment()->getShippingAddress() !== null
            ) {
                continue;
            }

            $shipmentTransfer = $itemTransfer->getShipment() ?: $quoteShipment;
            if ($shipmentTransfer === null) {
                $shipmentTransfer = (new ShipmentTransfer())
                    ->setMethod(new ShipmentMethodTransfer());
            }

            if ($shipmentExpenseTransfer === null && $itemTransfer->getShipment() !== null) {
                $shipmentExpenseTransfer = $itemTransfer->getShipment()->getExpense();
            }

            $shipmentTransfer->setExpense($shipmentExpenseTransfer)
                ->setShippingAddress($shippingAddress);
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function saveOrderShipmentTransaction(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderTransfer = $this->salesFacade->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $shipmentGroups = $this->shipmentService->groupItemsByShipment($saveOrderTransfer->getOrderItems());

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            $this->saveShipmentAddressTransfer($shipmentGroupTransfer);
            $this->addExpensesToOrder($shipmentGroupTransfer, $salesOrderTransfer, $saveOrderTransfer);
            $idSalesShipment = $this->entityManager->createSalesShipment(
                $shipmentGroupTransfer->getShipment(),
                $salesOrderTransfer->getIdSalesOrder()
            );
            $this->updateItemsShipment($shipmentGroupTransfer, $idSalesShipment);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return void
     */
    protected function saveShipmentAddressTransfer(ShipmentGroupTransfer $shipmentGroupTransfer): void
    {
        $shippingAddressTransfer = $shipmentGroupTransfer->getShipment()->getShippingAddress();
        $shippingAddressTransfer = $this->salesFacade->createOrderAddress($shippingAddressTransfer);
        $shipmentGroupTransfer->getShipment()->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $salesOrderTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function addExpensesToOrder(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        OrderTransfer $salesOrderTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $expenseTransfer = $this->sanitizeExpenseSumPrices($shipmentGroupTransfer->getShipment()->getExpense());
        $expenseTransfer->setFkSalesOrder($salesOrderTransfer->getIdSalesOrder());
        $expenseTransfer = $this->salesFacade->createSalesExpense($expenseTransfer);

        $shipmentGroupTransfer
            ->getShipment()
            ->getExpense()
            ->setIdSalesExpense($expenseTransfer->getIdSalesExpense());

        $salesOrderTransfer->addExpense($expenseTransfer);
        $saveOrderTransfer->addOrderExpense($expenseTransfer);
    }

    /**
     * @deprecated For BC reasons the missing sum prices are mirrored from unit prices
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function sanitizeExpenseSumPrices(ExpenseTransfer $expenseTransfer)
    {
        $expenseTransfer->setSumGrossPrice($expenseTransfer->getSumGrossPrice() ?? $expenseTransfer->getUnitGrossPrice());
        $expenseTransfer->setSumNetPrice($expenseTransfer->getSumNetPrice() ?? $expenseTransfer->getUnitNetPrice());
        $expenseTransfer->setSumPrice($expenseTransfer->getSumPrice() ?? $expenseTransfer->getUnitPrice());
        $expenseTransfer->setSumTaxAmount($expenseTransfer->getSumTaxAmount() ?? $expenseTransfer->getUnitTaxAmount());
        $expenseTransfer->setSumDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation() ?? $expenseTransfer->getUnitDiscountAmountAggregation());
        $expenseTransfer->setSumPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation() ?? $expenseTransfer->getUnitPriceToPayAggregation());

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param int $idSalesShipment
     *
     * @return void
     */
    protected function updateItemsShipment(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        int $idSalesShipment
    ): void {
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $this->entityManager->updateSalesOrderItemFkShipment($itemTransfer, $idSalesShipment);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertShipmentRequirements(QuoteTransfer $quoteTransfer): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireShipment();
            $itemTransfer->getShipment()->requireMethod();
            $itemTransfer->getShipment()->requireShippingAddress();
        }
    }
}
