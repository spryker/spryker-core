<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\CurrencyDecisionRule;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use SprykerTest\Zed\Discount\Business\BaseRuleTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group DecisionRule
 * @group CurrencyDecisionRuleTest
 * Add your own group annotations below this line
 */
class CurrencyDecisionRuleTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testDecisionRuleWhenCurrencyMatchesShouldReturnTrue()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer $clauseTransfer, $currency) {
            return $clauseTransfer->getValue() === $currency;
        });

        $currencyDecisionRule = $this->createCurrencyDecisionRuleMock($comparatorMock);
        $isSatisfied = $currencyDecisionRule->isSatisfiedBy(
            $this->createQuoteTransfer(),
            $this->createItemTransfer(1000),
            $this->createClauseTransfer('EUR')
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $comparatorMock
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\CurrencyDecisionRule
     */
    protected function createCurrencyDecisionRuleMock(?ComparatorOperatorsInterface $comparatorMock = null)
    {
        if ($comparatorMock === null) {
            $comparatorMock = $this->createComparatorMock();
        }

        return new CurrencyDecisionRule($comparatorMock);
    }
}
