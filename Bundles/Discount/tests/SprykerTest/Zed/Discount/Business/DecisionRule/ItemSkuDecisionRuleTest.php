<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\ItemSkuDecisionRule;
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
 * @group ItemSkuDecisionRuleTest
 * Add your own group annotations below this line
 */
class ItemSkuDecisionRuleTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testDecisionRuleWhenCurrentItemSkuMatchesShouldReturnTrue()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer $clauseTransfer, $itemSku) {
            return $clauseTransfer->getValue() === $itemSku;
        });

        $itemSkuDecisionRule = $this->createItemSkuDecisionRule($comparatorMock);
        $isSatisfied = $itemSkuDecisionRule->isSatisfiedBy(
            $this->createQuoteTransfer(),
            $this->createItemTransfer(1000, 5, 'sku-123'),
            $this->createClauseTransfer('sku-123')
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $comparatorMock
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemSkuDecisionRule
     */
    protected function createItemSkuDecisionRule(?ComparatorOperatorsInterface $comparatorMock = null)
    {
        if ($comparatorMock === null) {
            $comparatorMock = $this->createComparatorMock();
        }

        return new ItemSkuDecisionRule($comparatorMock);
    }
}
