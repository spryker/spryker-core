<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaver as CheckoutShipmentOrderSaver;

class ShipmentGroupSaver extends CheckoutShipmentOrderSaver implements ShipmentGroupSaverInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     */
    public function __construct(
        ShipmentEntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return void
     */
    public function updateShipmentTransaction(ShipmentGroupTransfer $shipmentGroupTransfer, OrderTransfer $salesOrderTransfer): void
    {
        $this->saveShipmentAddressTransfer($shipmentGroupTransfer);

        $expenseTransfer = $this->sanitizeExpenseSumPrices($shipmentGroupTransfer->getShipment()->getExpense());
        $expenseTransfer->setFkSalesOrder($salesOrderTransfer->getIdSalesOrder());
        $expenseTransfer = $this->salesFacade->createSalesExpense($expenseTransfer);

        $shipmentGroupTransfer
            ->getShipment()
            ->getExpense()
            ->setIdSalesExpense($expenseTransfer->getIdSalesExpense());

        $salesOrderTransfer->addExpense($expenseTransfer);

        $idSalesShipment = $this->entityManager->createSalesShipment(
            $shipmentGroupTransfer->getShipment(),
            $salesOrderTransfer->getIdSalesOrder()
        );

        foreach ($quoteTransfer->getShipmentGroups() as $shipmentGroupTransfer) {
            $this->updateItemsShipment($shipmentGroupTransfer, $idSalesShipment);
        }
    }
}
