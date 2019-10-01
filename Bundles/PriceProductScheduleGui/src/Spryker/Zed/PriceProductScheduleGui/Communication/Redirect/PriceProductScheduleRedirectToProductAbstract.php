<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Redirect;

use Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer;

class PriceProductScheduleRedirectToProductAbstract implements PriceProductScheduleRedirectInterface
{
    protected const REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN = '/product-management/edit?id-product-abstract=%d#tab-content-scheduled_prices';

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer
     */
    public function makeRedirectUrl(PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer): PriceProductScheduleRedirectTransfer
    {
        $priceProductScheduleRedirectTransfer->requireIdProductAbstract();

        $idProductAbstract = $priceProductScheduleRedirectTransfer->getIdProductAbstract();

        $redirectUrl = sprintf(static::REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);

        return $priceProductScheduleRedirectTransfer->setRedirectUrl($redirectUrl);
    }
}
