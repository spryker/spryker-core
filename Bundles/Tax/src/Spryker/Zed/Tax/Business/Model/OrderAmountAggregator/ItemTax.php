<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface;

class ItemTax implements OrderAmountAggregatorInterface
{
    /**
     * @var \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @param \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(PriceCalculationHelperInterface $priceCalculationHelper)
    {
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertItemTaxRequirements($orderTransfer);

        $this->addTaxAmountToTaxableItems($orderTransfer->getItems());
        $this->addTaxAmountToTaxableItems($orderTransfer->getExpenses());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[]|\Generated\Shared\Transfer\ItemTransfer[] $taxableItems
     *
     * @return void
     */
    protected function addTaxAmountToTaxableItems(\ArrayObject $taxableItems)
    {
        foreach ($taxableItems as $item) {
            if (empty($item->getTaxRate())) {
                continue;
            }

            $item->setUnitTaxAmount(
                $this->priceCalculationHelper->getTaxValueFromPrice(
                    $item->getUnitGrossPrice(),
                    $item->getTaxRate()
                )
            );

            $item->setSumTaxAmount(
                $this->priceCalculationHelper->getTaxValueFromPrice(
                    $item->getSumGrossPrice(),
                    $item->getTaxRate()
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertItemTaxRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireItems();
    }
}
