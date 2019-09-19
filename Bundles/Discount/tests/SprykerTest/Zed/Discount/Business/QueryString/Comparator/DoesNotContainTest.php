<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\DoesNotContain;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group DoesNotContainTest
 * Add your own group annotations below this line
 */
class DoesNotContainTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenDoesNotContainExpressionProvided()
    {
        $doesNotContain = $this->createDoesNotContains();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('does not contain');

        $isAccepted = $doesNotContain->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueNotExistingInClauseShouldReturnTrue()
    {
        $doesNotContain = $this->createDoesNotContains();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('not');

        $isMatching = $doesNotContain->compare($clauseTransfer, ' oNe TwO ');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueExistingInClauseShouldReturnFalse()
    {
        $contains = $this->createDoesNotContains();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('no');

        $isMatching = $contains->compare($clauseTransfer, ' no TwO ');

        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenNonScalarValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $contains = $this->createDoesNotContains();

        $clauseTransfer = new ClauseTransfer();

        $contains->compare($clauseTransfer, []);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\DoesNotContain
     */
    protected function createDoesNotContains()
    {
        return new DoesNotContain();
    }
}
