<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Less;

class LessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenLessExpressionProvided()
    {
        $less = $this->createLess();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('<');

        $isAccepted = $less->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueLessThanClauseShouldReturnTrue()
    {
        $less = $this->createLess();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('1');

        $isMatching = $less->compare($clauseTransfer, '2');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueNotLessThanClauseShouldReturnFalse()
    {
        $less = $this->createLess();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('2');

        $isMatching = $less->compare($clauseTransfer, '1');

        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenNonNumericValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $less = $this->createLess();

        $clauseTransfer = new ClauseTransfer();

        $less->compare($clauseTransfer, 'as');
    }

    /**
     * @return Less
     */
    protected function createLess()
    {
        return new Less();
    }
}
