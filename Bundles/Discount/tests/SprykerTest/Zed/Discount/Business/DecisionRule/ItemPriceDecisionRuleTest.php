<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\ItemPriceDecisionRule;
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
 * @group ItemPriceDecisionRuleTest
 * Add your own group annotations below this line
 */
class ItemPriceDecisionRuleTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testDecisionRuleWhenCurrentItemPriceMatchesShouldReturnTrue()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer $clauseTransfer, $itemPrice) {
            return $clauseTransfer->getValue() === $itemPrice;
        });

        $itemPriceDecisionRule = $this->createItemPriceDecisionRule($comparatorMock);
        $isSatisfied = $itemPriceDecisionRule->isSatisfiedBy(
            $this->createQuoteTransfer(),
            $this->createItemTransfer(1000),
            $this->createClauseTransfer(10)
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $comparatorMock
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemPriceDecisionRule
     */
    protected function createItemPriceDecisionRule(?ComparatorOperatorsInterface $comparatorMock = null)
    {
        if ($comparatorMock === null) {
            $comparatorMock = $this->createComparatorMock();
        }

        $currencyConverterMock = $this->createCurrencyConverterMock();
        $currencyConverterMock->method('convertDecimalToCent')->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
            return $clauseTransfer->setValue($clauseTransfer->getValue() * 100);
        });

        return new ItemPriceDecisionRule($comparatorMock, $currencyConverterMock);
    }
}
