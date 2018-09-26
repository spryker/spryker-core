<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use DateTime;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;

class CalendarWeekDecisionRule implements DecisionRuleInterface
{
    public const DATE_FORMAT = 'W';

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected $comparators;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparators
     */
    public function __construct(ComparatorOperatorsInterface $comparators)
    {
        $this->comparators = $comparators;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        $calendarWeek = $this->getCalendarWeek();

        return $this->comparators->compare($clauseTransfer, $calendarWeek);
    }

    /**
     * @return string
     */
    protected function getCalendarWeek()
    {
        $currentDateTime = $this->getCurrentDateTime();
        $calendarWeek = $currentDateTime->format(self::DATE_FORMAT);

        return $calendarWeek;
    }

    /**
     * @return \DateTime
     */
    protected function getCurrentDateTime()
    {
        return new DateTime();
    }
}
