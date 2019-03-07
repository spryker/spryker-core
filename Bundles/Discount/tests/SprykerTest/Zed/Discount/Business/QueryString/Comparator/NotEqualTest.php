<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\NotEqual;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group NotEqualTest
 * Add your own group annotations below this line
 */
class NotEqualTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenNotEaualExpressionProvided()
    {
        $notEqual = $this->createNotEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('!=');

        $isAccepted = $notEqual->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @dataProvider compareWhenClauseValueIsNotEqualToProvidedShouldReturnTrueProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenClauseValueIsNotEqualToProvidedShouldReturnTrue(ClauseTransfer $clauseTransfer, string $withValue): void
    {
        $notEqual = $this->createNotEqual();

        $isMatching = $notEqual->compare($clauseTransfer, $withValue);

        $this->assertTrue($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenClauseValueIsNotEqualToProvidedShouldReturnTrueProvider(): array
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
     * @dataProvider compareWhenClauseValueIsEqualToProvidedProvidedShouldReturnFalseProvider
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return void
     */
    public function testCompareWhenClauseValueIsEqualToProvidedProvidedShouldReturnFalse(ClauseTransfer $clauseTransfer, string $withValue): void
    {
        $more = $this->createNotEqual();

        $isMatching = $more->compare($clauseTransfer, $withValue);

        $this->assertFalse($isMatching);
    }

    /**
     * @return array
     */
    public function compareWhenClauseValueIsEqualToProvidedProvidedShouldReturnFalseProvider(): array
    {
        return [
            'int stock' => $this->createClauseData('1', '1'),
            'float stock' => $this->createClauseData('1.1', '1.1'),
        ];
    }

    /**
     * @return void
     */
    public function testCompareWhenNonNumericValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $notEqual = $this->createNotEqual();

        $clauseTransfer = new ClauseTransfer();

        $notEqual->compare($clauseTransfer, []);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\NotEqual
     */
    protected function createNotEqual()
    {
        return new NotEqual($this->tester->getLocator()->discount()->service());
    }
}
