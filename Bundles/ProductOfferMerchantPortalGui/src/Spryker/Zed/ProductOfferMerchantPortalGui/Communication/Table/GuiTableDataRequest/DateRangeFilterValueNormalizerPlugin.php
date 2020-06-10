<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest;

use DateTime;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 */
class DateRangeFilterValueNormalizerPlugin extends AbstractPlugin implements FilterValueNormalizerPluginInterface
{
    protected const FILTER_DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.uP';
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

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
