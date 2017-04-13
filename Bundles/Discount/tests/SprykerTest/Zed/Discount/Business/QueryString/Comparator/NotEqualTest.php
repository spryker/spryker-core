<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\NotEqual;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group NotEqualTest
 */
class NotEqualTest extends PHPUnit_Framework_TestCase
{

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
     * @return void
     */
    public function testCompareWhenClauseValueIsNotEqualToProvidedShouldReturnTrue()
    {
        $notEqual = $this->createNotEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('2');

        $isMatching = $notEqual->compare($clauseTransfer, '1');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenClauseValueIsEqualToProvidedProvidedShouldReturnFalse()
    {
        $more = $this->createNotEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('1');

        $isMatching = $more->compare($clauseTransfer, '1');

        $this->assertFalse($isMatching);
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
        return new NotEqual();
    }

}
