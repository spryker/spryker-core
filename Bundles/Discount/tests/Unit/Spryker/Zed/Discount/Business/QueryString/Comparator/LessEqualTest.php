<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\LessEqual;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group LessEqualTest
 */
class LessEqualTest extends \PHPUnit_Framework_TestCase
{

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
     * @return void
     */
    public function testCompareWhenValueLessClauseShouldReturnTrue()
    {
        $lessEqual = $this->createLessEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('2');

        $isMatching = $lessEqual->compare($clauseTransfer, '1');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueEqualClauseShouldReturnTrue()
    {
        $lessEqual = $this->createLessEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('1');

        $isMatching = $lessEqual->compare($clauseTransfer, '1');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueNotLessThanClauseShouldReturnFalse()
    {
        $lessEqual = $this->createLessEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('1');

        $isMatching = $lessEqual->compare($clauseTransfer, '2');

        $this->assertFalse($isMatching);
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
        return new LessEqual();
    }

}
