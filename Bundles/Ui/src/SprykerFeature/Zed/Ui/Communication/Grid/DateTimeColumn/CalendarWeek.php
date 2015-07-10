<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\WeekFormat;

class CalendarWeek extends FilterFormatAbstract
{

    /**
     * @return mixed
     */
    public function getTimeRangeGenerator()
    {
        $format = $this->getUnambiguousFormat();

        if (!$format) {
            return;
        }

        $carbonDate = Carbon::now();

        $weekOfYear = $carbonDate->weekOfYear;
        $requestedWeeK = str_replace('CW ', '', $format->getFormat());

        $diff = $requestedWeeK - $weekOfYear;

        $carbonDate->addWeeks($diff);

        if ($carbonDate->isFuture()) {
            $carbonDate->subYear();
        }

        return $format->getTimeRangeGenerator($carbonDate);
    }

    /**
     * @return array|TimeRangeFormat\TimeRangeFormatAbstract[]
     */
    protected function getFormats()
    {
        return WeekFormat::getInstancesFromArray([
            'CW 1',
            'CW 2',
            'CW 3',
            'CW 4',
            'CW 5',
            'CW 6',
            'CW 7',
            'CW 8',
            'CW 9',
            'CW 10',
            'CW 11',
            'CW 12',
            'CW 13',
            'CW 14',
            'CW 15',
            'CW 16',
            'CW 17',
            'CW 18',
            'CW 19',
            'CW 20',
            'CW 21',
            'CW 22',
            'CW 23',
            'CW 24',
            'CW 25',
            'CW 26',
            'CW 27',
            'CW 28',
            'CW 29',
            'CW 30',
            'CW 31',
            'CW 32',
            'CW 33',
            'CW 34',
            'CW 35',
            'CW 36',
            'CW 37',
            'CW 38',
            'CW 39',
            'CW 40',
            'CW 41',
            'CW 42',
            'CW 43',
            'CW 44',
            'CW 45',
            'CW 46',
            'CW 47',
            'CW 48',
            'CW 49',
            'CW 50',
            'CW 51',
            'CW 52',
        ]);
    }

}
