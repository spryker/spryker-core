<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Equal;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group EqualTest
 * Add your own group annotations below this line
 */
class EqualTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenEqualExpressionProvided()
    {
        $equal = $this->createEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('=');

        $isAccepted = $equal->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @dataProvider compareWhenValueMatchingClauseShouldReturnTrueProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenValueMatchingClauseShouldReturnTrue(ClauseTransfer $clauseTransfer, string $withValue)
    {
        $equal = $this->createEqual();

        $isMatching = $equal->compare($clauseTransfer, $withValue);

        $this->assertTrue($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenValueMatchingClauseShouldReturnTrueProvider(): array
    {
        return [
            'int stock' => $this->createClauseData('1', '1'),
            'float stock' => $this->createClauseData('1.5', '1.5'),
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
     * @dataProvider compareWhenValueNotMatchingClauseShouldReturnFalseProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenValueNotMatchingClauseShouldReturnFalse(ClauseTransfer $clauseTransfer, string $withValue)
    {
        $equal = $this->createEqual();

        $isMatching = $equal->compare($clauseTransfer, $withValue);

        $this->assertFalse($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenValueNotMatchingClauseShouldReturnFalseProvider(): array
    {
        return [
            'int stock' => $this->createClauseData('2', '1'),
            'float stock' => $this->createClauseData('2.3', '2.4'),
        ];
    }

    /**
     * @return void
     */
    public function testCompareWhenNonScalarValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $equal = $this->createEqual();

        $clauseTransfer = new ClauseTransfer();

        $equal->compare($clauseTransfer, []);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Equal
     */
    protected function createEqual()
    {
        return new Equal($this->tester->getLocator()->discount()->service());
    }
}
