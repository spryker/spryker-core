<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\MonthDecisionRule;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Unit\Spryker\Zed\Discount\Business\BaseRuleTester;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group DecisionRule
 * @group MonthDecisionRuleTest
 */
class MonthDecisionRuleTest extends BaseRuleTester
{

    /**
     * @return void
     */
    public function testDecisionRuleShouldReturnTrueIfGivenDateMatchesClause()
    {
        $dateTime = new \DateTime();

        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer  $clauseTransfer, $currentMonth) {
            return $clauseTransfer->getValue() === $currentMonth;
        });

        $monthDecisionRule = $this->createMonthDecisionRule($comparatorMock, $dateTime);
        $isSatisfied =$monthDecisionRule->isSatisfiedBy(
            $this->createQuoteTransfer(),
            $this->createItemTransfer(),
            $this->createClauseTransfer($dateTime->format(MonthDecisionRule::DATE_FORMAT))
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparatorMock
     * @param \DateTime $currentDateTime
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\CalendarWeekDecisionRule
     */
    protected function createMonthDecisionRule(
        ComparatorOperatorsInterface $comparatorMock,
        \DateTime $currentDateTime
    ) {

        $calendarWeekDecisionRule = $this->getMock(
            MonthDecisionRule::class,
            ['getCurrentDateTime'],
            [$comparatorMock]
        );

        $calendarWeekDecisionRule->method('getCurrentDateTime')->willReturn($currentDateTime);

        return $calendarWeekDecisionRule;
    }

}
