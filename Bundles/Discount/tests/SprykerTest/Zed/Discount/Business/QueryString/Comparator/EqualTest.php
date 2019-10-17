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
 *
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
     * @return void
     */
    public function testCompareWhenValueMatchingClauseShouldReturnTrue()
    {
        $equal = $this->createEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('1');

        $isMatching = $equal->compare($clauseTransfer, '1');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueNotMatchingClauseShouldReturnFalse()
    {
        $equal = $this->createEqual();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('2');

        $isMatching = $equal->compare($clauseTransfer, '1');

        $this->assertFalse($isMatching);
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
        return new Equal();
    }
}
