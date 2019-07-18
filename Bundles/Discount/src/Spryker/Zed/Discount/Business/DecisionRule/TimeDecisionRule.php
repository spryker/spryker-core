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

class TimeDecisionRule implements DecisionRuleInterface
{
    public const TIME_FORMAT = 'H:i';

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

        $currentTime = $this->convertToSeconds(
            $this->getCurrentTime()
        );

        $compareWithTime = $this->convertToSeconds(
            $clauseTransfer->getValue()
        );

        $clauseTransfer->setValue($compareWithTime);

        return $this->comparators->compare($clauseTransfer, $currentTime);
    }

    /**
     * @param string $timeFormatted
     *
     * @return int
     */
    protected function convertToSeconds($timeFormatted)
    {
        return strtotime("1970-01-01 $timeFormatted UTC");
    }

    /**
     * @return string
     */
    protected function getCurrentTime()
    {
        $currentDate = $this->getCurrentDateTime();
        $time = $currentDate->format(self::TIME_FORMAT);

        return $time;
    }

    /**
     * @return \DateTime
     */
    protected function getCurrentDateTime()
    {
        return new DateTime();
    }
}
