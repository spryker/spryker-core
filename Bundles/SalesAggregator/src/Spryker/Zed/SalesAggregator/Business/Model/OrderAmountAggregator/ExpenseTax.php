<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface;

class ExpenseTax implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface $taxFacade
     */
    public function __construct(SalesAggregatorToTaxInterface $taxFacade)
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
        $this->addTaxAmountToTaxableItems($orderTransfer->getExpenses());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[]| $taxableItems
     *
     * @return void
     */
    protected function addTaxAmountToTaxableItems(\ArrayObject $taxableItems)
    {
        foreach ($taxableItems as $item) {
            if (empty($item->getTaxRate())) {
                continue;
            }
            $item->requireUnitGrossPrice()->requireSumGrossPrice();

            $item->setUnitTaxAmount(
                $this->taxFacade->getTaxAmountFromGrossPrice(
                    $item->getUnitGrossPrice(),
                    $item->getTaxRate()
                )
            );

            $item->setSumTaxAmount(
                $this->taxFacade->getTaxAmountFromGrossPrice(
                    $item->getSumGrossPrice(),
                    $item->getTaxRate()
                )
            );
        }
    }

}
