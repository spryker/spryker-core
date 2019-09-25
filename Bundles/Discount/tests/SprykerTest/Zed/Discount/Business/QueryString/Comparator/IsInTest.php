<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn;
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
 * @group IsInTest
 * Add your own group annotations below this line
 */
class IsInTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptShouldReturnTrueWhenIsInExpressionProvided()
    {
        $isIn = $this->createIsIn();

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('is in');

        $isAccepted = $isIn->accept($clauseTransfer);

        $this->assertTrue($isAccepted);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueIsInClauseShouldReturnTrue()
    {
        $isIn = $this->createIsIn();

        $clauseTransfer = new ClauseTransfer();
        $list = implode(ComparatorOperators::LIST_DELIMITER, [1, 2, 3]);

        $clauseTransfer->setValue($list);

        $isMatching = $isIn->compare($clauseTransfer, '1');

        $this->assertTrue($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenValueIsNotInClauseShouldReturnFalse()
    {
        $isIn = $this->createIsIn();

        $clauseTransfer = new ClauseTransfer();

        $list = implode(ComparatorOperators::LIST_DELIMITER, [2, 3]);
        $clauseTransfer->setValue($list);

        $isMatching = $isIn->compare($clauseTransfer, '1');

        $this->assertFalse($isMatching);
    }

    /**
     * @return void
     */
    public function testCompareWhenNonScalarValueUsedShouldThrowException()
    {
        $this->expectException(ComparatorException::class);

        $isIn = $this->createIsIn();

        $clauseTransfer = new ClauseTransfer();

        $isIn->compare($clauseTransfer, []);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn
     */
    protected function createIsIn()
    {
        return new IsIn();
    }
}
