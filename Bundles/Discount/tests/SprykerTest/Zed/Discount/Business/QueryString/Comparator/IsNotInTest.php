<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Comparator
 * @group IsNotInTest
 * Add your own group annotations below this line
 */
class IsNotInTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenIsNotInExpressionProvided()
    {
        $isNotIn = $this->createIsNotIn();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('is not in');

        $isAccepted = $isNotIn->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueIsNotInClauseShouldReturnTrue()
    {
        $equal = $this->createIsNotIn();

        $clauseTransfer = new ClauseTransfer();
        $list = implode(ComparatorOperators::LIST_DELIMITER, [2, 3]);
        $clauseTransfer->setValue($list);

        $isMatching = $equal->compare($clauseTransfer, '1');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueIsInClauseShouldReturnFalse()
    {
        $contains = $this->createIsNotIn();

        $clauseTransfer = new ClauseTransfer();
        $list = implode(ComparatorOperators::LIST_DELIMITER, [1, 2, 3]);
        $clauseTransfer->setValue($list);

        $isMatching = $contains->compare($clauseTransfer, '1');

        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenNonScalarValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $contains = $this->createIsNotIn();

        $clauseTransfer = new ClauseTransfer();

        $contains->compare($clauseTransfer, []);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn
     */
    protected function createIsNotIn()
    {
        return new IsNotIn();
    }
}
