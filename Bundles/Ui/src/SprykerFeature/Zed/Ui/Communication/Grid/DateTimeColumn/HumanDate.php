<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\DayFormat;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\MonthFormat;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\WeekFormat;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\YearFormat;

class HumanDate extends FilterFormatAbstract
{

    /**
     */
    public function getTimeRangeGenerator()
    {
        $format = $this->getUnambiguousFormat();

        if (!$format) {
            return;
        }

        $carbonDate = new Carbon($format->getFormat());

        return $format->getTimeRangeGenerator($carbonDate);
    }

    /**
     * @return array
     */
    protected function getFormats()
    {
        $yearFormats = YearFormat::getInstancesFromArray([
            'Last year',
            'This year',
        ]);

        $monthFormats = MonthFormat::getInstancesFromArray([
            'Last month',
            'This month',
        ]);

        $weekFormats = WeekFormat::getInstancesFromArray([
            'Last week',
            'This week',
        ]);

        $dayFormats = DayFormat::getInstancesFromArray([
            'Today',
            'Yesterday',
            'Tomorrow',
        ]);

        return array_merge($yearFormats, $monthFormats, $weekFormats, $dayFormats);
    }

}
