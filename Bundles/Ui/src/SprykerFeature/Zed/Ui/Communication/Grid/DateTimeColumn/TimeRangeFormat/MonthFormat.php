<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\TimeRangeFormat;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeGenerator\MonthRangeGenerator;

class MonthFormat extends TimeRangeFormatAbstract
{

    /**
     * @param Carbon $carbonDate
     *
     * @return MonthRangeGenerator
     */
    public function getTimeRangeGenerator(Carbon $carbonDate)
    {
        return new MonthRangeGenerator($carbonDate);
    }

}
