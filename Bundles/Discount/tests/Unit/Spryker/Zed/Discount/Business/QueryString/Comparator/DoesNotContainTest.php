<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\DoesNotContain;

class DoesNotContainTest extends \PHPUnit_Framework_TestCase
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
        $clauseTransfer->setValue('oNe TwO');

        $isMatching = $doesNotContain->compare($clauseTransfer, ' not ');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueExistingInClauseShouldReturnFalse()
    {
        $contains = $this->createDoesNotContains();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue('oNe TwO');

        $isMatching = $contains->compare($clauseTransfer, ' one ');

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
     * @return DoesNotContain
     */
    protected function createDoesNotContains()
    {
        return new DoesNotContain();
    }
}
