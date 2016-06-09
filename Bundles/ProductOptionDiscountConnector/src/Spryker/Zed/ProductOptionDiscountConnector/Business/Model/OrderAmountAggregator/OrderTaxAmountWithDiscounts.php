<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxBridgeInterface;

class OrderTaxAmountWithDiscounts implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxBridgeInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxBridgeInterface $taxFacade
     */
    public function __construct(ProductOptionToTaxBridgeInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $totalTaxAmount = 0;
        $totalTaxAmount += $this->sumItemTax($orderTransfer);
        $totalTaxAmount += $this->sumExpenseTax($orderTransfer);

        $this->setTaxTotals($orderTransfer, $totalTaxAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $totalTaxAmount
     *
     * @return void
     */
    protected function setTaxTotals(OrderTransfer $orderTransfer, $totalTaxAmount)
    {
        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount($totalTaxAmount);

        $orderTransfer->getTotals()->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function sumExpenseTax(OrderTransfer $orderTransfer)
    {
        $totalTaxAmount = 0;
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $totalTaxAmount += $expenseTransfer->getSumTaxAmount();
        }

        return $totalTaxAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function sumItemTax(OrderTransfer $orderTransfer)
    {
        $totalTaxAmount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $totalTaxAmount += $itemTransfer->getSumTaxAmountWithProductOptionAndDiscountAmounts();
        }

        return $totalTaxAmount;
    }

}
