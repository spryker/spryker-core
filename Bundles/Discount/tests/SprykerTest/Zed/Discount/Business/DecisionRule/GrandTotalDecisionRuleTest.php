<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\GrandTotalDecisionRule;
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
 * @group GrandTotalDecisionRuleTest
 * Add your own group annotations below this line
 */
class GrandTotalDecisionRuleTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testWhenGrandTotalMatchesShouldReturnTrue()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer $clauseTransfer, $grandTotal) {
            return $clauseTransfer->getValue() === $grandTotal;
        });

        $grandTotalDecisionRule = $this->createGrandTotalDecisionRule($comparatorMock);

        $quoteTransfer = $this->createQuoteTransfer();
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setGrandTotal(1000);
        $quoteTransfer->setTotals($totalTransfer);

        $isSatisfied = $grandTotalDecisionRule->isSatisfiedBy(
            $quoteTransfer,
            $this->createItemTransfer(),
            $this->createClauseTransfer(10)
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @return void
     */
    public function testWhenGrandTotalNotMatchingShouldReturnFalse()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer $clauseTransfer, $grandTotal) {
            return $clauseTransfer->getValue() === $grandTotal;
        });

        $grandTotalDecisionRule = $this->createGrandTotalDecisionRule($comparatorMock);

        $quoteTransfer = $this->createQuoteTransfer();
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setGrandTotal(1200);
        $quoteTransfer->setTotals($totalTransfer);

        $isSatisfied = $grandTotalDecisionRule->isSatisfiedBy(
            $quoteTransfer,
            $this->createItemTransfer(),
            $this->createClauseTransfer(10)
        );

        $this->assertFalse($isSatisfied);
    }

    /**
     * @return void
     */
    public function testWhenGrandTotalIsNotSetShouldReturnFalse()
    {
        $grandTotalDecisionRule = $this->createGrandTotalDecisionRule();

        $isSatisfied = $grandTotalDecisionRule->isSatisfiedBy(
            $this->createQuoteTransfer(),
            $this->createItemTransfer(),
            $this->createClauseTransfer(10)
        );

        $this->assertFalse($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $comparatorMock
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\GrandTotalDecisionRule
     */
    protected function createGrandTotalDecisionRule(?ComparatorOperatorsInterface $comparatorMock = null)
    {
        if ($comparatorMock === null) {
            $comparatorMock = $this->createComparatorMock();
        }

        $currencyConverterMock = $this->createCurrencyConverterMock();
        $currencyConverterMock->method('convertDecimalToCent')->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
            return $clauseTransfer->setValue($clauseTransfer->getValue() * 100);
        });

        return new GrandTotalDecisionRule($comparatorMock, $currencyConverterMock);
    }
}
