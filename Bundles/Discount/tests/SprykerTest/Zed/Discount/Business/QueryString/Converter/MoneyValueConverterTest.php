<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Converter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverter;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyBridge;
use Spryker\Zed\Money\Business\MoneyFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Converter
 * @group MoneyValueConverterTest
 * Add your own group annotations below this line
 */
class MoneyValueConverterTest extends Unit
{
    /**
     * @return void
     */
    public function testConvertDecimalToCentWhenIsNotInUsedShouldUpdateAllItems()
    {
        $currencyConverterMock = $this->createMoneyValueConverter();

        $values = ['10', '12.12', '12,30'];
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue(implode(ComparatorOperators::LIST_DELIMITER, $values));
        $clauseTransfer->setOperator(IsNotIn::EXPRESSION);

        $currencyConverterMock->convertDecimalToCent($clauseTransfer);

        $convertedValues = explode(ComparatorOperators::LIST_DELIMITER, $clauseTransfer->getValue());

        $this->assertEquals(1000, $convertedValues[0]);
        $this->assertEquals(1212, $convertedValues[1]);
        $this->assertEquals(1230, $convertedValues[2]);
    }

    /**
     * @return void
     */
    public function testConvertDecimalToCentWhenIsInUsedShouldUpdateAllItems()
    {
        $currencyConverterMock = $this->createMoneyValueConverter();

        $values = ['10', '12.12', '12,30'];
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue(implode(ComparatorOperators::LIST_DELIMITER, $values));
        $clauseTransfer->setOperator(IsIn::EXPRESSION);

        $currencyConverterMock->convertDecimalToCent($clauseTransfer);

        $convertedValues = explode(ComparatorOperators::LIST_DELIMITER, $clauseTransfer->getValue());

        $this->assertEquals(1000, $convertedValues[0]);
        $this->assertEquals(1212, $convertedValues[1]);
        $this->assertEquals(1230, $convertedValues[2]);
    }

    /**
     * @return void
     */
    public function testConvertDecimalToCentWhenSingleValueUsedShouldUpdateAllItems()
    {
        $currencyConverterMock = $this->createMoneyValueConverter();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('10,50');
        $clauseTransfer->setOperator(IsIn::EXPRESSION);

        $currencyConverterMock->convertDecimalToCent($clauseTransfer);

        $this->assertEquals(1050, $clauseTransfer->getValue());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface
     */
    protected function createMoneyValueConverter()
    {
        $discountToMoneyBridge = new DiscountToMoneyBridge(new MoneyFacade());

        return new MoneyValueConverter($discountToMoneyBridge);
    }
}
