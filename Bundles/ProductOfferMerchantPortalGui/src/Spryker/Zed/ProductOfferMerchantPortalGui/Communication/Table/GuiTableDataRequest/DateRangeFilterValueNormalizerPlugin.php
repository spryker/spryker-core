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

        $from = $value['from'] ?? null;
        if ($from) {
            $from = DateTime::createFromFormat(DateTime::ATOM, $from)->format(self::DATE_TIME_FORMAT);
        }

        $to = $value['to'] ?? null;
        if ($to) {
            $to = DateTime::createFromFormat(DateTime::ATOM, $to)->format(self::DATE_TIME_FORMAT);
        }

        return (new CriteriaRangeFilterTransfer())
            ->setFrom($from)
            ->setTo($to);
    }
}
