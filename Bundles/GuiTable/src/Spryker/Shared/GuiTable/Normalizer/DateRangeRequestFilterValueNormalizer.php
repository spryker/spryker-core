<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Normalizer;

use DateTime;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use RuntimeException;

class DateRangeRequestFilterValueNormalizer implements DateRangeRequestFilterValueNormalizerInterface
{
    /**
     * @var string
     */
    protected const FILTER_DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.uP';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param mixed $value
     *
     * @return \Generated\Shared\Transfer\CriteriaRangeFilterTransfer|null
     */
    public function normalizeFilterValue($value): ?CriteriaRangeFilterTransfer
    {
        if (!$value || !is_array($value) || (!isset($value['from']) && !isset($value['to']))) {
            return null;
        }

        /** @var string|null $fromDate */
        $fromDate = $value['from'] ?? null;
        if ($fromDate) {
            $date = DateTime::createFromFormat(static::FILTER_DATE_TIME_FORMAT, $fromDate);
            $fromDate = $date ? $date->format(static::DATE_TIME_FORMAT) :
                new RuntimeException(sprintf(
                    'Wrong filter value date format. Supported format is %s',
                    static::DATE_TIME_FORMAT,
                ));
        }

        /** @var string|null $toDate */
        $toDate = $value['to'] ?? null;
        if ($toDate) {
            $date = DateTime::createFromFormat(static::FILTER_DATE_TIME_FORMAT, $toDate);
            $toDate = $date ? $date->format(static::DATE_TIME_FORMAT) :
                new RuntimeException(sprintf(
                    'Wrong filter value date format. Supported format is %s',
                    static::DATE_TIME_FORMAT,
                ));
        }

        return (new CriteriaRangeFilterTransfer())
            ->setFrom($fromDate)
            ->setTo($toDate);
    }
}
