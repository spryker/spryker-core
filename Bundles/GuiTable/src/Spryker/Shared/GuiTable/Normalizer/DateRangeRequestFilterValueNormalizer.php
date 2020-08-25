<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Normalizer;

use DateTime;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;

class DateRangeRequestFilterValueNormalizer implements DateRangeRequestFilterValueNormalizerInterface
{
    protected const FILTER_DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.uP';
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param mixed $value
     *
     * @return \Generated\Shared\Transfer\CriteriaRangeFilterTransfer|null
     */
    public function normalizeFilterValue($value)
    {
        if (!$value || !is_array($value) || (!isset($value['from']) && !isset($value['to']))) {
            return null;
        }

        $fromDate = $value['from'] ?? null;
        if ($fromDate) {
            $fromDate = DateTime::createFromFormat(static::FILTER_DATE_TIME_FORMAT, $fromDate)
                ->format(self::DATE_TIME_FORMAT);
        }

        $toDate = $value['to'] ?? null;
        if ($toDate) {
            $toDate = DateTime::createFromFormat(static::FILTER_DATE_TIME_FORMAT, $toDate)
                ->format(self::DATE_TIME_FORMAT);
        }

        return (new CriteriaRangeFilterTransfer())
            ->setFrom($fromDate)
            ->setTo($toDate);
    }
}
