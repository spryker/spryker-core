<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\DayOfWeekDecisionRule;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Unit\Spryker\Zed\Discount\Business\BaseRuleTester;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group DecisionRule
 * @group DayOfTheWeekDecisionRuleTest
 */
class DayOfTheWeekDecisionRuleTest extends BaseRuleTester
{

    /**
     * @return void
     */
    public function testDecisionRuleShouldReturnTrueIfGivenDateMatchesClause()
    {
        $dateTime = new \DateTime();

        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer  $clauseTransfer, $calendarWeek) {
            return $clauseTransfer->getValue() === $calendarWeek;
        });

        $calendarWeekDecisionRule = $this->createDateOfTheWeekDecisionRule($comparatorMock, $dateTime);
        $isSatisfied = $calendarWeekDecisionRule->isSatisfiedBy(
            $this->createQuoteTransfer(),
            $this->createItemTransfer(),
            $this->createClauseTransfer($dateTime->format(DayOfWeekDecisionRule::DATE_FORMAT))
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparatorMock
     * @param \DateTime $currentDateTime
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\CalendarWeekDecisionRule
     */
    protected function createDateOfTheWeekDecisionRule(
        ComparatorOperatorsInterface $comparatorMock,
        \DateTime $currentDateTime
    ) {

        $calendarWeekDecisionRule = $this->getMock(
            DayOfWeekDecisionRule::class,
            ['getCurrentDateTime'],
            [$comparatorMock]
        );

        $calendarWeekDecisionRule->method('getCurrentDateTime')->willReturn($currentDateTime);

        return $calendarWeekDecisionRule;
    }

}
