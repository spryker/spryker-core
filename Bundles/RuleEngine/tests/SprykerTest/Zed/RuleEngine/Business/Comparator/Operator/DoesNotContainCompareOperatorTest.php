<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Comparator\Operator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\DoesNotContainCompareOperator;
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
 * @group DoesNotContainCompareOperatorTest
 * Add your own group annotations below this line
 */
class DoesNotContainCompareOperatorTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenDoesNotContainExpressionProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::OPERATOR => 'does not contain']))->build();

        // Act
        $isAccepted = $this->createDoesNotContainCompareOperator()->accept($ruleEngineClauseTransfer);

        // Assert
        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnTrueWhenValueNotExistingInClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => 'not']))->build();

        // Act
        $isMatching = $this->createDoesNotContainCompareOperator()->compare($ruleEngineClauseTransfer, ' oNe TwO ');

        // Assert
        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenValueExistingInClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => 'no']))->build();

        // Act
        $isMatching = $this->createDoesNotContainCompareOperator()->compare($ruleEngineClauseTransfer, ' no TwO ');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldThrowExceptionWhenNonScalarValueUsed(): void
    {
        // Assert
        $this->expectException(CompareOperatorException::class);

        // Arrange
        $ruleEngineClauseTransfer = new RuleEngineClauseTransfer();

        // Act
        $this->createDoesNotContainCompareOperator()->compare($ruleEngineClauseTransfer, []);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => 'value']))->build();

        // Act
        $isMatching = $this->createDoesNotContainCompareOperator()->compare($ruleEngineClauseTransfer, '');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testIsValueValidShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Act
        $isValueValid = $this->createDoesNotContainCompareOperator()->isValidValue('');

        // Assert
        $this->assertFalse($isValueValid);
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\DoesNotContainCompareOperator
     */
    protected function createDoesNotContainCompareOperator(): DoesNotContainCompareOperator
    {
        return new DoesNotContainCompareOperator();
    }
}
