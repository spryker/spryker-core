<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\DecisionRule;

use DateTime;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\DecisionRule\TimeDecisionRule;
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
 * @group TimeDecisionRuleTest
 * Add your own group annotations below this line
 */
class TimeDecisionRuleTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testDecisionRuleShouldReturnTrueIfGivenDateMatchesClause()
    {
        $dateTime = new DateTime();

        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->method('compare')->willReturnCallback(function (ClauseTransfer $clauseTransfer, $currentMonth) {
            return $clauseTransfer->getValue() === $currentMonth;
        });

        $monthDecisionRule = $this->createTimeDecisionRule($comparatorMock, $dateTime);
        $isSatisfied = $monthDecisionRule->isSatisfiedBy(
            $this->createQuoteTransfer(),
            $this->createItemTransfer(),
            $this->createClauseTransfer($dateTime->format(TimeDecisionRule::TIME_FORMAT))
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparatorMock
     * @param \DateTime $currentDateTime
     *
     * @return \Spryker\Zed\Discount\Business\DecisionRule\CalendarWeekDecisionRule
     */
    protected function createTimeDecisionRule(
        ComparatorOperatorsInterface $comparatorMock,
        DateTime $currentDateTime
    ) {
        $calendarWeekDecisionRule = $this->getMockBuilder(TimeDecisionRule::class)->setMethods(['getCurrentDateTime'])->setConstructorArgs([$comparatorMock])->getMock();

        $calendarWeekDecisionRule->method('getCurrentDateTime')->willReturn($currentDateTime);

        return $calendarWeekDecisionRule;
    }
}
