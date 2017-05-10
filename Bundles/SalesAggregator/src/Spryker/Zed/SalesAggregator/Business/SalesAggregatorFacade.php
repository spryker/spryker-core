<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesAggregator\Business\SalesAggregatorBusinessFactory getFactory()
 */
class SalesAggregatorFacade extends AbstractFacade implements SalesAggregatorFacadeInterface
{

    /**
     * Specification:
     *  - Reads order from database and stores in order transfer
     *  - Run all aggregation plugins defined in SalesAggregatorDependencyProvider
     *
     * @api
     *
     * @throw \Spryker\Zed\SalesAggregator\Business\Exception\OrderTotalHydrationException
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByIdSalesAggregatorOrder($idSalesOrder);
    }

    /**
     * Specification:
     *  - Reads order from database and stores in order transfer
     *  - Run all item aggregation plugins defined in SalesAggregatorDependencyProvider
     *
     * @api
     *
     * @throw \Spryker\Zed\SalesAggregator\Business\Exception\OrderTotalHydrationException
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function getOrderItemTotalsByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByIdSalesAggregatorOrderItem($idSalesOrderItem);
    }

    /**
     *  Specification:
     *  - Use existing OrderTransfer instead fo quering database
     *  - Run all item aggregation plugins defined in SalesAggregatorDependencyProvider
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalByOrderTransfer(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByOrderTransfer($orderTransfer);
    }

    /**
     * Specification:
     *  - Iterate order exepenses and sum up amounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseAmounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createExpenseOrderTotalAggregator()->aggregate($orderTransfer);
    }

    /**
     *  Specification:
     *  - Sum up expenses and subtotals
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderGrandTotal(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createGrandTotalOrderTotalAggregator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Sum up item amounts before discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemAmounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createItemOrderOrderAggregator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Sum up order subtotal
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderSubtotal(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createSubtotalOrderAggregator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Calculate item tax amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemTaxAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderItemTaxAmountAggregator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Calculate order total tax amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTaxAmountAggregator(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderTaxAmountAggregator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Calculate order expense tax amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxAmountAggregator(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderExpenseTaxAmountAggregator()->aggregate($orderTransfer);
    }

    /**
     *
     * Specification:
     *  - Loops over all items and calculates gross amount for each items
     *  - Data is read from sales order persistence
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemProductOptionGrossPrice(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createItemProductOptionGrossPriceAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Loops over all items and calculates subtotal
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderSubtotalWithProductOptions(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createSubtotalWithProductOption()
            ->aggregate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createOrderDiscountTotalAmount()
            ->aggregate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createSalesOrderTotalsAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createItemTotalOrderAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateGrandTotalWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createSalesOrderGrandTotalAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createOrderExpenseTaxWithDiscountsAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpensesWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createOrderExpenseWithDiscountsAggregator()
            ->aggregate($orderTransfer);
    }


    /**
     * Specification:
     *  - Loops over product option calculated discounts and sums up to order total
     *  - Amounts stored in OrderTransfer->getCalculatedDiscounts()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscountsWithProductOptions(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createProductOptionOrderDiscountAggregator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Loops over product option discount with discount sum
     *  - Calculates totals with product options
     *  - Amounts stored: OrderTransfer->getTotals()->setDiscountTotal()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmountWithProductOptions(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createDiscountTotalWithProductOptionsCalculator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Read order discounts from persistence
     *  - Assign discount to each coresponding item
     *  - Calculate item and product option discount amount fields with discount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemWithProductOptionsDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createProductOptionDiscountCalculator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Loops over items with discounts
     *  - Calculate discount amount for items after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemWithProductOptionsAndDiscountsTaxAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createItemProductOptionsAndDiscountsTaxCalculator()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Loops over items with options and discounts
     *  - Loops over expenses with discounts
     *  - Sum all tax amounts calculated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalTaxAmountWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderTotalWithDiscountsTaxCalculator()->aggregate($orderTransfer);
    }

}
