<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Dependency\Facade;

interface CalculationToTaxInterface
{

    /**
     * @param int $grossPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate);

    /**
     * @param int $netPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromNetPrice($netPrice, $taxRate);

    /**
     * @return void
     */
    public function resetAccruedTaxCalculatorRoundingErrorDelta();
}
