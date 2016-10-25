<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Tax\Business\Model;

use Spryker\Zed\Tax\Business\Model\PriceCalculationHelper;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Tax
 * @group Business
 * @group Model
 * @group TaxCalculatorHelperTest
 */
class TaxCalculatorHelperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testTaxValueFromTax()
    {
        $taxCalculatorHelper = $this->createPriceCalculationHelper();

        $netValueFromPrice = $taxCalculatorHelper->getNetValueFromPrice(100, 19);

        $this->assertEquals(84, $netValueFromPrice);
    }

    /**
     * @return void
     */
    public function testTaxValueFroPrice()
    {
        $taxCalculatorHelper = $this->createPriceCalculationHelper();

        $netValueFromPrice = $taxCalculatorHelper->getTaxValueFromPrice(100, 19);

        $this->assertEquals(16, $netValueFromPrice);
    }

    /**
     * @return void
     */
    public function testTaxRateFromPrice()
    {
        $taxCalculatorHelper = $this->createPriceCalculationHelper();

        $netValueFromPrice = $taxCalculatorHelper->getTaxValueFromPrice(100, 84);

        $this->assertEquals(46, $netValueFromPrice);
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\PriceCalculationHelper
     */
    protected function createPriceCalculationHelper()
    {
        return new PriceCalculationHelper();
    }

}
