<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Propel\Runtime\Propel;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Spryker\Shared\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConstants;
use Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainerInterface;

class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{

    /**
     * @var ShipmentCheckoutConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(ShipmentCheckoutConnectorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        Propel::getConnection()->beginTransaction();
        $salesOrderEntity = $this->queryContainer->querySalesOrderById($orderTransfer->getIdSalesOrder())->findOne();
        $salesOrderEntity->setFkShipmentMethod($orderTransfer->getIdShipmentMethod());

        $expenses = $orderTransfer->getExpenses();
        foreach ($expenses as $expenseTransfer) {
            if (ShipmentCheckoutConnectorConstants::SHIPMENT_EXPENSE_TYPE === $expenseTransfer->getType()) {
                $salesOrderExpense = new SpySalesExpense();
                $salesOrderExpense->fromArray($expenseTransfer->toArray());

                $taxSetTransfer = $expenseTransfer->getTaxSet();
                if ($taxSetTransfer !== null) {
                    $salesOrderExpense->setTaxPercentage($taxSetTransfer->getEffectiveRate());
                }
                $salesOrderExpense->save();
                $expenseTransfer->setIdSalesExpense($salesOrderExpense->getIdSalesExpense());

                $salesOrderEntity->addExpense($salesOrderExpense);
            }
        }
        $salesOrderEntity->save();
        Propel::getConnection()->commit();
    }

}
