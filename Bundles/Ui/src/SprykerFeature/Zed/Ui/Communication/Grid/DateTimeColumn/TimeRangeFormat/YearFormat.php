<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\TimeRangeFormat;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Communication\Plugin\Grid\DateTimeColumn\TimeRangeGenerator\YearRangeGenerator;

class YearFormat extends TimeRangeFormatAbstract
{

    /**
     * @param Carbon $carbonDate
     *
     * @return YearRangeGenerator
     */
    public function getTimeRangeGenerator(Carbon $carbonDate)
    {
        return new YearRangeGenerator($carbonDate);
    }

}
