<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect;

use Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer;

class PriceProductScheduleRedirectToScheduleList implements PriceProductScheduleRedirectInterface
{
    protected const REDIRECT_URL_SCHEDULE_LIST_PATTERN = '/price-product-schedule-gui/edit-schedule-list?id-price-product-schedule-list=%d';

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer
     */
    public function makeRedirectUrl(
        PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
    ): PriceProductScheduleRedirectTransfer {
        $priceProductScheduleRedirectTransfer->requireIdPriceProductScheduleList();

        $redirectUrl = sprintf(
            static::REDIRECT_URL_SCHEDULE_LIST_PATTERN,
            $priceProductScheduleRedirectTransfer->getIdPriceProductScheduleList()
        );

        return $priceProductScheduleRedirectTransfer->setRedirectUrl($redirectUrl);
    }
}
