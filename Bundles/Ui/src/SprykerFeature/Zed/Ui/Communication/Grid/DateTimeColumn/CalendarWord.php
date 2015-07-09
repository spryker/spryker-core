<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\DayFormat;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\MonthFormat;

class CalendarWord extends FilterFormatAbstract
{

    /**
     * @return mixed|null
     */
    public function getTimeRangeGenerator()
    {
        $format = $this->getUnambiguousFormat();

        if (!$format) {
            return;
        }

        $timeString = $format->getFormat();

        $carbonDate = new Carbon($timeString);

        if ($carbonDate->isFuture()) {
            if ($format instanceof DayFormat) {
                $carbonDate->subWeek();
            } else {
                $carbonDate->subYear();
            }
        }

        return $format->getTimeRangeGenerator($carbonDate);
    }

    /**
     * @return array|\SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\TimeRangeFormatAbstract[]
     */
    protected function getFormats()
    {
        $monthFormats = MonthFormat::getInstancesFromArray([
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ]);

        $dayFormats = DayFormat::getInstancesFromArray([
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ]);

        return array_merge($monthFormats, $dayFormats);
    }

}
