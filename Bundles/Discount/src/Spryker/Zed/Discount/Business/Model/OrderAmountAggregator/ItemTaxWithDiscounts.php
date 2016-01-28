<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToTaxBridgeInterface;

class ItemTaxWithDiscounts implements OrderAmountAggregatorInterface
{
    /**
     * @var DiscountToTaxBridgeInterface
     */
    protected $taxFacade;

    /**
     * ItemTaxWithDiscounts constructor.
     */
    public function __construct(DiscountToTaxBridgeInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param OrderTransfer $orderTransfer
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->addExpenseTaxes($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     */
    protected function addExpenseTaxes(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if (empty($expenseTransfer->getTaxRate())) {
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
