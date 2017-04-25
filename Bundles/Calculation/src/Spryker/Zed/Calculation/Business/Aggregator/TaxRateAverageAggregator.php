<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Aggregator;


use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface;

class TaxRateAverageAggregator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {

            $unitPriceToPayNetPrice = $itemTransfer->getUnitPriceToPayAggregation() - $itemTransfer->getUnitTaxAmountFullAggregation();
            $taxRateAverageAggregation = round(($itemTransfer->getUnitPriceToPayAggregation() / $unitPriceToPayNetPrice - 1) * 100, 2) ;

            $itemTransfer->setTaxRateAverageAggregation($taxRateAverageAggregation);

        }
    }
}
