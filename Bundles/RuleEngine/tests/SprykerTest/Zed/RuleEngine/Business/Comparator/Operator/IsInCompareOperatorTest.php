<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Comparator\Operator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\IsInCompareOperator;
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
 * @group IsInCompareOperatorTest
 * Add your own group annotations below this line
 */
class IsInCompareOperatorTest extends Unit
{
    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Comparator\Comparator::LIST_DELIMITER
     *
     * @var string
     */
    protected const LIST_DELIMITER = ';';

    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenIsInExpressionProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([RuleEngineClauseTransfer::OPERATOR => 'is in']))->build();

        // Act
        $isAccepted = $this->createIsInCompareOperator()->accept($ruleEngineClauseTransfer);

        // Assert
        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnTrueWhenValueIsInClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::VALUE => implode(static::LIST_DELIMITER, [1, 2, 3]),
        ]))->build();

        // Act
        $isMatching = $this->createIsInCompareOperator()->compare($ruleEngineClauseTransfer, '1');

        // Assert
        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenValueIsNotInClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::VALUE => implode(static::LIST_DELIMITER, [1, 2, 3]),
        ]))->build();

        // Act
        $isMatching = $this->createIsInCompareOperator()->compare($ruleEngineClauseTransfer, '4');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenNonScalarValueUsedShouldThrowException(): void
    {
        // Assert
        $this->expectException(CompareOperatorException::class);

        // Arrange
        $ruleEngineClauseTransfer = new RuleEngineClauseTransfer();

        // Act
        $this->createIsInCompareOperator()->compare($ruleEngineClauseTransfer, []);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnTrueWhenAtLeastOneOfProvidedValuesIsInClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::VALUE => implode(static::LIST_DELIMITER, [1, 2, 3]),
        ]))->build();
        $valueToCompare = implode(static::LIST_DELIMITER, [1, 4]);

        // Act
        $isMatching = $this->createIsInCompareOperator()->compare($ruleEngineClauseTransfer, $valueToCompare);

        // Assert
        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenNoOneOfProvidedValuesIsInClause(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::VALUE => implode(static::LIST_DELIMITER, [1, 2, 3]),
        ]))->build();
        $valueToCompare = implode(static::LIST_DELIMITER, [4, 5]);

        // Act
        $isMatching = $this->createIsInCompareOperator()->compare($ruleEngineClauseTransfer, $valueToCompare);

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Arrange
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::VALUE => implode(static::LIST_DELIMITER, [1, 2, 3]),
        ]))->build();

        // Act
        $isMatching = $this->createIsInCompareOperator()->compare($ruleEngineClauseTransfer, '');

        // Assert
        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testIsValidValueShouldReturnFalseWhenEmptyValueIsProvided(): void
    {
        // Act
        $isValidValue = $this->createIsInCompareOperator()->isValidValue('');

        // Assert
        $this->assertFalse($isValidValue);
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\IsInCompareOperator
     */
    protected function createIsInCompareOperator(): IsInCompareOperator
    {
        return new IsInCompareOperator();
    }
}
