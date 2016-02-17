<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxBridgeInterface;

class ItemProductOptionTaxWithDiscounts implements OrderAmountAggregatorInterface
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
        $this->addTaxWithProductOptions($orderTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function addTaxWithProductOptions(\ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $effectiveTaxRate = $this->getEffectiveTaxRateForItem($itemTransfer);
            if (empty($effectiveTaxRate)) {
                continue;
            }

            $itemTransfer->requireUnitGrossPriceWithProductOptionAndDiscountAmounts()
                ->requireSumGrossPriceWithProductOptionAndDiscountAmounts();

            $unitTaxAmount = $this->taxFacade->getTaxAmountFromGrossPrice(
                $itemTransfer->getUnitGrossPriceWithProductOptionAndDiscountAmounts(), $effectiveTaxRate
            );

            $sumTaxAmount = $this->taxFacade->getTaxAmountFromGrossPrice(
                $itemTransfer->getSumGrossPriceWithProductOptionAndDiscountAmounts(), $effectiveTaxRate
            );

            $itemTransfer->setUnitTaxAmountWithProductOptionAndDiscountAmounts($unitTaxAmount);
            $itemTransfer->setSumTaxAmountWithProductOptionAndDiscountAmounts($sumTaxAmount);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getEffectiveTaxRateForItem(ItemTransfer $itemTransfer)
    {
        $taxRates = [];

        $taxRates[] = $itemTransfer->getTaxRate();
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $taxRates[] = $productOptionTransfer->getTaxRate();
        }

        $totalTaxRates = array_sum($taxRates);
        if (empty($totalTaxRates)) {
            return 0;
        }

        return $totalTaxRates / count($taxRates);
    }

}
