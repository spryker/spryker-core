<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Comparator\Operator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\ContainsCompareOperator;
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
 * @group ContainsCompareOperatorTest
 * Add your own group annotations below this line
 */
class ContainsCompareOperatorTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenContainsExpressionProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::OPERATOR => 'contains']))->build();

        // Act
        $isAccepted = $this->createContainsCompareOperator()->accept($ruleEngineClauseTransfer);

        // Assert
        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnTrueWhenValueExistsInClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => 'TwO']))->build();

        // Act
        $isMatching = $this->createContainsCompareOperator()->compare($ruleEngineClauseTransfer, 'oNe TwO');

        // Assert
        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenValueNotExistingInClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => 'zero']))->build();

        // Act
        $isMatching = $this->createContainsCompareOperator()->compare($ruleEngineClauseTransfer, ' oNe TwO ');

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
        $this->createContainsCompareOperator()->compare($ruleEngineClauseTransfer, []);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::VALUE => 'value']))->build();

        // Act
        $isMatching = $this->createContainsCompareOperator()->compare($ruleEngineClauseTransfer, '');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testIsValueValidShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Act
        $isValueValid = $this->createContainsCompareOperator()->isValidValue('');

        // Assert
        $this->assertFalse($isValueValid);
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\ContainsCompareOperator
     */
    protected function createContainsCompareOperator(): ContainsCompareOperator
    {
        return new ContainsCompareOperator();
    }
}
