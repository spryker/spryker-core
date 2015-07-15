<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\TimeRangeGenerator;

use Carbon\Carbon;

abstract class TimeRangeGeneratorAbstract implements TimeRangeGeneratorInterface
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

    abstract public function getStartDateTimeString();

    abstract public function getEndDateTimeString();

}
