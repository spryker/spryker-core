<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Propel\Runtime\Propel;
use Spryker\Shared\Shipment\ShipmentConstants;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainerInterface;

class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{

    /**
     * @var \Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainerInterface
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        Propel::getConnection()->beginTransaction();
        $idSalesOrder = $checkoutResponse->getSaveOrder()->getIdSalesOrder();
        $salesOrderEntity = $this->queryContainer->querySalesOrderById($idSalesOrder)->findOne();
        $salesOrderEntity->setFkShipmentMethod($quoteTransfer->getShipmentMethod()->getIdShipmentMethod());

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if (ShipmentConstants::SHIPMENT_EXPENSE_TYPE === $expenseTransfer->getType()) {
                $salesOrderExpenseEntity = new SpySalesExpense();
                $this->hydrateOrderExpenseEntity($salesOrderExpenseEntity, $expenseTransfer);
                $salesOrderExpenseEntity->save();

                $expenseTransfer->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());

                $salesOrderEntity->addExpense($salesOrderExpenseEntity);
            }
        }
        $salesOrderEntity->save();
        Propel::getConnection()->commit();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function hydrateOrderExpenseEntity(
        SpySalesExpense $salesOrderExpenseEntity,
        ExpenseTransfer $expenseTransfer
    ) {
        $salesOrderExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesOrderExpenseEntity->setGrossPrice($expenseTransfer->getUnitGrossPrice());
        $salesOrderExpenseEntity->setPriceToPay($expenseTransfer->getUnitGrossPrice());
        $salesOrderExpenseEntity->setTaxPercentage($expenseTransfer->getTaxRate());
    }

}
