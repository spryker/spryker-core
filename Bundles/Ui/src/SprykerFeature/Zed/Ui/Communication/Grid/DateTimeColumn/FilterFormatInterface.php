<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn;

use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeGenerator\TimeRangeGeneratorInterface;

interface FilterFormatInterface
{

    /**
     * @return TimeRangeGeneratorInterface
     */
    public function getTimeRangeGenerator();

    /**
     * @return array
     */
    public function getSuggestions();

}
