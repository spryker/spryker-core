<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest;

use DateTime;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;

class DateRangeFilterValueNormalizerPlugin implements FilterValueNormalizerPluginInterface
{
    /**
     * @param string $filterType
     *
     * @return bool
     */
    public function isApplicable(string $filterType): bool
    {
        return $filterType === AbstractTable::FILTER_TYPE_DATE_RANGE;
    }

    /**
     * @param mixed $value
     *
     * @return \Generated\Shared\Transfer\CriteriaRangeFilterTransfer|null
     */
    public function normalizeValue($value)
    {
        if (!$value || is_array($value) || (!array_key_exists('from', $value) && !array_key_exists('to', $value))) {
            return null;
        }

        $from = $value['from'] ?? null;
        if ($from) {
            $from = DateTime::createFromFormat(DateTime::ATOM, $from);
        }

        $to = $value['to'] ?? null;
        if ($to) {
            $to = DateTime::createFromFormat(DateTime::ATOM, $to);
        }

        return (new CriteriaRangeFilterTransfer())
            ->setFrom($from)
            ->setTo($to);
    }
}
