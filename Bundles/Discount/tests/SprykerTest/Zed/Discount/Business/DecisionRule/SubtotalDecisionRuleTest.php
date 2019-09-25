<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\SubTotalDecisionRule;
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
 * @group SubtotalDecisionRuleTest
 * Add your own group annotations below this line
 */
class SubtotalDecisionRuleTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testWhenSubTotalMatchesClauseShouldReturnTrue()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer $clauseTransfer, $grandTotal) {
            return $clauseTransfer->getValue() === $grandTotal;
        });

        $subtotalDecisionRule = $this->createSubtotalDecisionRule($comparatorMock);

        $quoteTransfer = $this->createQuoteTransfer();
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setSubtotal(1000);
        $quoteTransfer->setTotals($totalTransfer);

        $isSatisfied = $subtotalDecisionRule->isSatisfiedBy(
            $quoteTransfer,
            $this->createItemTransfer(),
            $this->createClauseTransfer(10)
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @return void
     */
    public function testWhenSubTotalsNotSetShouldReturnFalse()
    {
        $subtotalDecisionRule = $this->createSubtotalDecisionRule();

        $isSatisfied = $subtotalDecisionRule->isSatisfiedBy(
            $this->createQuoteTransfer(),
            $this->createItemTransfer(),
            $this->createClauseTransfer(10)
        );

        $this->assertFalse($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $comparatorMock
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\SubTotalDecisionRule
     */
    protected function createSubtotalDecisionRule(?ComparatorOperatorsInterface $comparatorMock = null)
    {
        if ($comparatorMock === null) {
            $comparatorMock = $this->createComparatorMock();
        }

        $currencyConverterMock = $this->createCurrencyConverterMock();
        $currencyConverterMock->method('convertDecimalToCent')->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
            return $clauseTransfer->setValue($clauseTransfer->getValue() * 100);
        });

        return new SubTotalDecisionRule($comparatorMock, $currencyConverterMock);
    }
}
