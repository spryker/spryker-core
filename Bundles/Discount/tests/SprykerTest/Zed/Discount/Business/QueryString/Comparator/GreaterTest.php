<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Greater;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group GreaterTest
 * Add your own group annotations below this line
 */
class GreaterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenMoreExpressionProvided()
    {
        $more = $this->createMore();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('>');

        $isAccepted = $more->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @dataProvider compareWhenClauseValueIsBiggerThanProvidedShouldReturnTrueProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenClauseValueIsBiggerThanProvidedShouldReturnTrue(ClauseTransfer $clauseTransfer, string $withValue): void
    {
        $more = $this->createMore();

        $isMatching = $more->compare($clauseTransfer, $withValue);

        $this->assertTrue($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenClauseValueIsBiggerThanProvidedShouldReturnTrueProvider(): array
    {
        return [
            'int stock' => $this->createClauseData('1', '2'),
            'float stock' => $this->createClauseData('1.1', '1.2'),
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
     * @dataProvider compareWhenClauseValueIsSmallerThanProvidedShouldReturnFalseProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenClauseValueIsSmallerThanProvidedShouldReturnFalse(ClauseTransfer $clauseTransfer, string $withValue): void
    {
        $more = $this->createMore();

        $isMatching = $more->compare($clauseTransfer, $withValue);

        $this->assertFalse($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenClauseValueIsSmallerThanProvidedShouldReturnFalseProvider(): array
    {
        return [
            'int stock' => $this->createClauseData('2', '1'),
            'float stock' => $this->createClauseData('1.2', '1.1'),
        ];
    }

    /**
     * @return void
     */
    public function testCompareWhenNonNumericValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $more = $this->createMore();

        $clauseTransfer = new ClauseTransfer();

        $more->compare($clauseTransfer, 'as');
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Greater
     */
    protected function createMore()
    {
        return new Greater();
    }
}
