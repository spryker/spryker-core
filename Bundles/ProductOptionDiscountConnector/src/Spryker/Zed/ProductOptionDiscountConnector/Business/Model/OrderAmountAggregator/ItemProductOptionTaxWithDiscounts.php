<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface;

class ItemProductOptionTaxWithDiscounts implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     */
    public function __construct(ProductOptionToTaxInterface $taxFacade)
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
                $itemTransfer->getUnitGrossPriceWithProductOptionAndDiscountAmounts(),
                $effectiveTaxRate
            );

            $sumTaxAmount = $this->taxFacade->getTaxAmountFromGrossPrice(
                $itemTransfer->getSumGrossPriceWithProductOptionAndDiscountAmounts(),
                $effectiveTaxRate
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
