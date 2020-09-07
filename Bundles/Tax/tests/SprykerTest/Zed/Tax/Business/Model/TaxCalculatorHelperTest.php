<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Business
 * @group Model
 * @group TaxCalculatorHelperTest
 * Add your own group annotations below this line
 */
class TaxCalculatorHelperTest extends Unit
{
    /**
     * @return void
     */
    public function testTaxValueFromTax(): void
    {
        $taxCalculatorHelper = $this->createPriceCalculationHelper();

        $netValueFromPrice = $taxCalculatorHelper->getNetValueFromPrice(100, 19);

        $this->assertSame(84, $netValueFromPrice);
    }

    /**
     * @return void
     */
    public function testTaxValueFroPrice(): void
    {
        $taxCalculatorHelper = $this->createPriceCalculationHelper();

        $netValueFromPrice = $taxCalculatorHelper->getTaxValueFromPrice(100, 19);

        $this->assertSame(16, $netValueFromPrice);
    }

    /**
     * @return void
     */
    public function testTaxRateFromPrice(): void
    {
        $taxCalculatorHelper = $this->createPriceCalculationHelper();

        $netValueFromPrice = $taxCalculatorHelper->getTaxValueFromPrice(100, 84);

        $this->assertSame(46, $netValueFromPrice);
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\PriceCalculationHelper
     */
    protected function createPriceCalculationHelper(): PriceCalculationHelper
    {
        return new PriceCalculationHelper();
    }
}
