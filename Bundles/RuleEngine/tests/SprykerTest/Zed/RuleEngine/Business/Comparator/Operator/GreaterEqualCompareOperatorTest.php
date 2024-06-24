<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Comparator\Operator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\GreaterEqualCompareOperator;
use Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group RuleEngine
 * @group Business
 * @group Comparator
 * @group Operator
 * @group GreaterEqualCompareOperatorTest
 * Add your own group annotations below this line
 */
class GreaterEqualCompareOperatorTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenGreaterOrEqualExpressionProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::OPERATOR => '>=']))->build();

        // Act
        $isAccepted = $this->createGreaterEqualCompareOperator()->accept($ruleEngineClauseTransfer);

        // Assert
        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnTrueWhenProvidedValueIsBiggerThanClauseValue(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => '1']))->build();

        $isMatching = $this->createGreaterEqualCompareOperator()->compare($ruleEngineClauseTransfer, '2');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenProvidedValueIsSmallerThanClauseValue(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => '2']))->build();

        // Act
        $isMatching = $this->createGreaterEqualCompareOperator()->compare($ruleEngineClauseTransfer, '1');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnTrueWhenClauseValueIsEqual(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => '1']))->build();

        // Act
        $isMatching = $this->createGreaterEqualCompareOperator()->compare($ruleEngineClauseTransfer, '1');

        // Assert
        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldThrowExceptionWhenNonNumericValueProvided(): void
    {
        // Assert
        $this->expectException(CompareOperatorException::class);

        // Arrange
        $ruleEngineClauseTransfer = new RuleEngineClauseTransfer();

        // Act
        $this->createGreaterEqualCompareOperator()->compare($ruleEngineClauseTransfer, 'as');
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => '1']))->build();

        // Act
        $isMatching = $this->createGreaterEqualCompareOperator()->compare($ruleEngineClauseTransfer, '');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testIsValueValidShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Act
        $isValueValid = $this->createGreaterEqualCompareOperator()->isValidValue('');

        // Assert
        $this->assertFalse($isValueValid);
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\GreaterEqualCompareOperator
     */
    protected function createGreaterEqualCompareOperator(): GreaterEqualCompareOperator
    {
        return new GreaterEqualCompareOperator();
    }
}
