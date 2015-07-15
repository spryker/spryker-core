<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\TimeRangeFormat;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeGenerator\DayRangeGenerator;

class DayFormat extends TimeRangeFormatAbstract
{

    /**
     * @param Carbon $carbonDate
     *
     * @return DayRangeGenerator
     */
    public function getTimeRangeGenerator(Carbon $carbonDate)
    {
        return new DayRangeGenerator($carbonDate);
    }

}
