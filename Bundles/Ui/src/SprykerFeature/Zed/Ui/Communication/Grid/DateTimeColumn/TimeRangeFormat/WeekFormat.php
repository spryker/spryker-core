<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\TimeRangeFormat;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Communication\Plugin\Grid\DateTimeColumn\TimeRangeGenerator\WeekRangeGenerator;

class WeekFormat extends TimeRangeFormatAbstract
{

    /**
     * @param Carbon $carbonDate
     *
     * @return WeekRangeGenerator
     */
    public function getTimeRangeGenerator(Carbon $carbonDate)
    {
        return new WeekRangeGenerator($carbonDate);
    }

}
