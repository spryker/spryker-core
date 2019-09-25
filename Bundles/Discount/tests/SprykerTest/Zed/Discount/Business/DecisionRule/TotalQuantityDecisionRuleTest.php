<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\TotalQuantityDecisionRule;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use SprykerTest\Zed\Discount\Business\BaseRuleTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group DecisionRule
 * @group TotalQuantityDecisionRuleTest
 * Add your own group annotations below this line
 */
class TotalQuantityDecisionRuleTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testWhenTotalQuantityMatchesClauseShouldReturnTrue()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer $clauseTransfer, $grandTotal) {
            return $clauseTransfer->getValue() === $grandTotal;
        });

        $totalQuantityDecisionRule = $this->createTotalQuantityDecisionRule($comparatorMock);

        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->addItem($this->createItemTransfer(50, 1));
        $quoteTransfer->addItem($this->createItemTransfer(50, 2));
        $quoteTransfer->addItem($this->createItemTransfer(50, 5));

        $isSatisfied = $totalQuantityDecisionRule->isSatisfiedBy(
            $quoteTransfer,
            $this->createItemTransfer(),
            $this->createClauseTransfer(8)
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $comparatorMock
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\GrandTotalDecisionRule
     */
    protected function createTotalQuantityDecisionRule(?ComparatorOperatorsInterface $comparatorMock = null)
    {
        if ($comparatorMock === null) {
            $comparatorMock = $this->createComparatorMock();
        }

        return new TotalQuantityDecisionRule($comparatorMock);
    }
}
