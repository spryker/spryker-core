<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString\Converter;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn;
use Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverter;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Converter
 * @group MoneyValueConverterTest
 */
class MoneyValueConverterTest extends \PHPUnit_Framework_TestCase
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
        $currencyManagerMock = $this->createCurrencyMangerMock();
        $currencyManagerMock->method('convertDecimalToCent')->willReturnCallback(function ($amount) {
            return ($amount * 100);
        });

        return new MoneyValueConverter($currencyManagerMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Library\Currency\CurrencyManagerInterface
     */
    protected function createCurrencyMangerMock()
    {
        return $this->getMock(CurrencyManagerInterface::class);
    }

}
