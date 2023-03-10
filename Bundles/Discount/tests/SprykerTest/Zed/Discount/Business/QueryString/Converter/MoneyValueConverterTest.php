<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Converter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverter;
use Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface;
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
     * @uses \Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_IS_IN = 'is in';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_IS_NOT_IN = 'is not in';

    /**
     * @return void
     */
    public function testConvertDecimalToCentWhenIsNotInUsedShouldUpdateAllItems(): void
    {
        $currencyConverterMock = $this->createMoneyValueConverter();

        $values = ['10', '12.12', '12,30'];
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue(implode(ComparatorOperators::LIST_DELIMITER, $values));
        $clauseTransfer->setOperator(static::EXPRESSION_IS_NOT_IN);

        $currencyConverterMock->convertDecimalToCent($clauseTransfer);

        $convertedValues = explode(ComparatorOperators::LIST_DELIMITER, $clauseTransfer->getValue());

        $this->assertSame('1000', $convertedValues[0]);
        $this->assertSame('1212', $convertedValues[1]);
        $this->assertSame('1230', $convertedValues[2]);
    }

    /**
     * @return void
     */
    public function testConvertDecimalToCentWhenIsInUsedShouldUpdateAllItems(): void
    {
        $currencyConverterMock = $this->createMoneyValueConverter();

        $values = ['10', '12.12', '12,30'];
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue(implode(ComparatorOperators::LIST_DELIMITER, $values));
        $clauseTransfer->setOperator(static::EXPRESSION_IS_IN);

        $currencyConverterMock->convertDecimalToCent($clauseTransfer);

        $convertedValues = explode(ComparatorOperators::LIST_DELIMITER, $clauseTransfer->getValue());

        $this->assertSame('1000', $convertedValues[0]);
        $this->assertSame('1212', $convertedValues[1]);
        $this->assertSame('1230', $convertedValues[2]);
    }

    /**
     * @return void
     */
    public function testConvertDecimalToCentWhenSingleValueUsedShouldUpdateAllItems(): void
    {
        $currencyConverterMock = $this->createMoneyValueConverter();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('10,50');
        $clauseTransfer->setOperator(static::EXPRESSION_IS_IN);

        $currencyConverterMock->convertDecimalToCent($clauseTransfer);

        $this->assertSame('1050', $clauseTransfer->getValue());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface
     */
    protected function createMoneyValueConverter(): MoneyValueConverterInterface
    {
        $discountToMoneyBridge = new DiscountToMoneyBridge(new MoneyFacade());

        return new MoneyValueConverter($discountToMoneyBridge);
    }
}
