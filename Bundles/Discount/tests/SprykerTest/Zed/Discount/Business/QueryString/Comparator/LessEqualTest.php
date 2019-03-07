<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\LessEqual;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group LessEqualTest
 * Add your own group annotations below this line
 */
class LessEqualTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenLessEqualExpressionProvided()
    {
        $lessEqual = $this->createLessEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('<=');

        $isAccepted = $lessEqual->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @dataProvider compareWhenValueLessClauseShouldReturnTrueProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenValueLessClauseShouldReturnTrue(ClauseTransfer $clauseTransfer, string $withValue): void
    {
        $lessEqual = $this->createLessEqual();

        $isMatching = $lessEqual->compare($clauseTransfer, $withValue);

        $this->assertTrue($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenValueLessClauseShouldReturnTrueProvider(): array
    {
        return [
            'int stock' => $this->createClauseData('2', '1'),
            'float stock' => $this->createClauseData('1.2', '1.1'),
        ];
    }

    /**
     * @param string $clauseValue
     * @param string $withValue
     *
     * @return array
     */
    protected function createClauseData(string $clauseValue, string $withValue): array
    {
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue($clauseValue);

        return [$clauseTransfer, $withValue];
    }

    /**
     * @dataProvider compareWhenValueEqualClauseShouldReturnTrueProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenValueEqualClauseShouldReturnTrue(ClauseTransfer $clauseTransfer, string $withValue): void
    {
        $lessEqual = $this->createLessEqual();

        $isMatching = $lessEqual->compare($clauseTransfer, $withValue);

        $this->assertTrue($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenValueEqualClauseShouldReturnTrueProvider(): array
    {
        return [
            'int stock' => $this->createClauseData('1', '1'),
            'float stock' => $this->createClauseData('1.1', '1.1'),
        ];
    }

    /**
     * @dataProvider compareWhenValueNotLessThanClauseShouldReturnFalseProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenValueNotLessThanClauseShouldReturnFalse(ClauseTransfer $clauseTransfer, string $withValue): void
    {
        $lessEqual = $this->createLessEqual();

        $isMatching = $lessEqual->compare($clauseTransfer, $withValue);

        $this->assertFalse($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenValueNotLessThanClauseShouldReturnFalseProvider(): array
    {
        return [
            'int stock' => $this->createClauseData('1', '2'),
            'float stock' => $this->createClauseData('1.1', '1.2'),
        ];
    }

    /**
     * @return void
     */
    public function testCompareWhenNonNumericValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $lessEqual = $this->createLessEqual();

        $clauseTransfer = new ClauseTransfer();

        $lessEqual->compare($clauseTransfer, 'as');
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\LessEqual
     */
    protected function createLessEqual()
    {
        return new LessEqual($this->tester->getLocator()->discount()->service());
    }
}
