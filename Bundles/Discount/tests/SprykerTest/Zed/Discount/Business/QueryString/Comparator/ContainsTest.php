<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Contains;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group ContainsTest
 * Add your own group annotations below this line
 */
class ContainsTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenContainsExpressionProvided()
    {
        $contains = $this->createContains();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('contains');

        $isAccepted = $contains->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueExistsInClauseShouldReturnTrue()
    {
        $contains = $this->createContains();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('TwO');

        $isMatching = $contains->compare($clauseTransfer, 'oNe TwO');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueNotExistingInClauseShouldReturnFalse()
    {
        $contains = $this->createContains();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('zero');

        $isMatching = $contains->compare($clauseTransfer, ' oNe TwO ');

        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenNonScalarValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $contains = $this->createContains();

        $clauseTransfer = new ClauseTransfer();

        $contains->compare($clauseTransfer, []);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Contains
     */
    protected function createContains()
    {
        return new Contains();
    }
}
