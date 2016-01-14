<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Propel\Runtime\Propel;
use Spryker\Shared\Shipment\ShipmentConstants;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{

    /**
     * @var ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        Propel::getConnection()->beginTransaction();
        $idSalesOrder = $checkoutResponse->getSaveOrder()->getIdSalesOrder();
        $salesOrderEntity = $this->queryContainer->querySalesOrderById($idSalesOrder)->findOne();
        $salesOrderEntity->setFkShipmentMethod($quoteTransfer->getShipment()->getMethod()->getIdShipmentMethod());

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
     * @param SpySalesExpense $salesOrderExpenseEntity
     * @param ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function hydrateOrderExpenseEntity(
        SpySalesExpense $salesOrderExpenseEntity,
        ExpenseTransfer $expenseTransfer
    ) {
        $salesOrderExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesOrderExpenseEntity->setGrossPrice($expenseTransfer->getUnitGrossPrice());
    }

}
