<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest;

use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 */
class DateRangeFilterValueNormalizerPlugin extends AbstractPlugin implements FilterValueNormalizerPluginInterface
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
        if (!$value || !is_array($value) || (!isset($value['from']) && !isset($value['to']))) {
            return null;
        }

        $from = $value['from'] ?? null;
        if ($from) {
            $from = $this->getFactory()->getUtilDateTimeService()->formatToDbDateTime($from);
        }

        $to = $value['to'] ?? null;
        if ($to) {
            $to = $this->getFactory()->getUtilDateTimeService()->formatToDbDateTime($to);
        }

        return (new CriteriaRangeFilterTransfer())
            ->setFrom($from)
            ->setTo($to);
    }
}
