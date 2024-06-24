<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Comparator\Operator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\GreaterCompareOperator;
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
 * @group GreaterCompareOperatorTest
 * Add your own group annotations below this line
 */
class GreaterCompareOperatorTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenMoreExpressionProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::OPERATOR => '>']))->build();

        // Act
        $isAccepted = $this->createGreaterCompareOperator()->accept($ruleEngineClauseTransfer);

        // Assert
        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnTrueWhenProvidedValueIsGreaterThanClauseValue(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => '1']))->build();

        // Act
        $isMatching = $this->createGreaterCompareOperator()->compare($ruleEngineClauseTransfer, '2');

        // Assert
        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenProvidedValueIsLessThanClauseValue(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => '2']))->build();

        // Act
        $isMatching = $this->createGreaterCompareOperator()->compare($ruleEngineClauseTransfer, '1');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldThrowExceptionWhenNonNumericValueUsed(): void
    {
        // Assert
        $this->expectException(CompareOperatorException::class);

        // Arrange
        $ruleEngineClauseTransfer = new RuleEngineClauseTransfer();

        // Act
        $this->createGreaterCompareOperator()->compare($ruleEngineClauseTransfer, 'as');
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => '1']))->build();

        // Act
        $isMatching = $this->createGreaterCompareOperator()->compare($ruleEngineClauseTransfer, '');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testIsValueValidShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Act
        $isValueValid = $this->createGreaterCompareOperator()->isValidValue('');

        // Assert
        $this->assertFalse($isValueValid);
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\GreaterCompareOperator
     */
    protected function createGreaterCompareOperator(): GreaterCompareOperator
    {
        return new GreaterCompareOperator();
    }
}
