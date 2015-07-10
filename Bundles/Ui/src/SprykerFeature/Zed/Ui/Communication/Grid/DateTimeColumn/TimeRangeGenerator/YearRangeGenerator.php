<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\TimeRangeGenerator;

use Carbon\Carbon;

class YearRangeGenerator extends TimeRangeGeneratorAbstract
{

    /**
     * @var Carbon
     */
    protected $carbonDate;

    /**
     * @param Carbon $carbonDate
     */
    public function __construct(Carbon $carbonDate)
    {
        $this->carbonDate = $carbonDate;
    }

    /**
     * @return string
     */
    public function getStartDateTimeString()
    {
        return $this->carbonDate->startOfYear()->toDateTimeString();
    }

    /**
     * @return string
     */
    public function getEndDateTimeString()
    {
        return $this->carbonDate->endOfYear()->toDateTimeString();
    }

}
