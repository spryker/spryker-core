<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model;

use Generated\Shared\ShipmentCheckoutConnector\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Propel\Runtime\Propel;
use SprykerFeature\Shared\Shipment\ShipmentConstants;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainerInterface;

class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{

    /**
     * @var ShipmentCheckoutConnectorQueryContainerInterface
     */
    protected $queryContainer;

    public function __construct(ShipmentCheckoutConnectorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveShipmentForOrder(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        Propel::getConnection()->beginTransaction();
        $salesOrderEntity = $this->queryContainer->querySalesOrderById($orderTransfer->getIdSalesOrder())->findOne();
        $salesOrderEntity->setFkShipmentMethod($orderTransfer->getIdShipmentMethod());

        $expenses = $orderTransfer->getExpenses();
        foreach ($expenses as $expenseTransfer) {
            if (ShipmentConstants::SHIPMENT_EXPENSE_TYPE === $expenseTransfer->getType()) {
                $salesOrderExpense = new SpySalesExpense();
                $salesOrderExpense->fromArray($expenseTransfer->toArray());
                $salesOrderExpense->setTaxPercentage($expenseTransfer->getTaxSet()->getEffectiveRate());
                $salesOrderExpense->save();
                $expenseTransfer->setIdSalesExpense($salesOrderExpense->getIdSalesExpense());

                $salesOrderEntity->addExpense($salesOrderExpense);
            }
        }
        $salesOrderEntity->save();
        Propel::getConnection()->commit();
    }

}
