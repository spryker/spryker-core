<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToTaxBridgeInterface;

class OrderExpenseTaxWithDiscounts implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToTaxBridgeInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToTaxBridgeInterface $taxFacade
     */
    public function __construct(DiscountToTaxBridgeInterface $taxFacade)
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
        $this->addExpenseTaxes($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addExpenseTaxes(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if (!$expenseTransfer->getTaxRate()) {
                continue;
            }

            $expenseTransfer->setUnitTaxAmountWithDiscounts(
                $this->taxFacade->getTaxAmountFromGrossPrice(
                    $expenseTransfer->getUnitGrossPriceWithDiscounts(),
                    $expenseTransfer->getTaxRate()
                )
            );

            $expenseTransfer->setSumTaxAmountWithDiscounts(
                $this->taxFacade->getTaxAmountFromGrossPrice(
                    $expenseTransfer->getSumGrossPriceWithDiscounts(),
                    $expenseTransfer->getTaxRate()
                )
            );
        }
    }

}
