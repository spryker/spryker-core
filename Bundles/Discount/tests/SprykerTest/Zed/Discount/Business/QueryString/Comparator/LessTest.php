<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Less;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group LessTest
 */
class LessTest extends PHPUnit_Framework_TestCase
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
        $clauseTransfer->setValue('2');

        $isMatching = $less->compare($clauseTransfer, '1');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueNotLessThanClauseShouldReturnFalse()
    {
        $less = $this->createLess();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('1');

        $isMatching = $less->compare($clauseTransfer, '2');

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
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Less
     */
    protected function createLess()
    {
        return new Less();
    }

}
