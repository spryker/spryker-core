<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Zed\Discount\Business\DecisionRule\ItemPriceDecisionRule;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Unit\Spryker\Zed\Discount\Business\BaseRuleTester;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group DecisionRule
 * @group ItemPriceDecisionRuleTest
 */
class ItemPriceDecisionRuleTest extends BaseRuleTester
{

    /**
     * @return void
     */
    public function testDecisionRuleWhenCurrentItemPriceMatchesShouldReturnTrue()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer  $clauseTransfer, $itemPrice) {
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
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparatorMock
     * @param \Spryker\Shared\Library\Currency\CurrencyManagerInterface $currencyManagerMock
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemPriceDecisionRule
     */
    protected function createItemPriceDecisionRule(
        ComparatorOperatorsInterface $comparatorMock = null,
        CurrencyManagerInterface $currencyManagerMock = null
    ) {
        if ($comparatorMock === null) {
            $comparatorMock = $this->createComparatorMock();
        }

        if ($currencyManagerMock === null) {
            $currencyManagerMock = $this->createCurrencyCoverterMock();
            $currencyManagerMock->method('convertDecimalToCent')->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
                return $clauseTransfer->setValue($clauseTransfer->getValue() * 100);
            });
        }

        return new ItemPriceDecisionRule($comparatorMock, $currencyManagerMock);
    }

}
