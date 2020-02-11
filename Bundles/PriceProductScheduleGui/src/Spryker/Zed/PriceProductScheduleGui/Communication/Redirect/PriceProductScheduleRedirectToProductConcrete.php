<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Redirect;

use Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer;

class PriceProductScheduleRedirectToProductConcrete implements PriceProductScheduleRedirectInterface
{
    protected const REDIRECT_URL_PRODUCT_CONCRETE_PATTERN = '/product-management/edit/variant?id-product=%d&id-product-abstract=%d#tab-content-scheduled_prices';

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer
     */
    public function makeRedirectUrl(PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer): PriceProductScheduleRedirectTransfer
    {
        $priceProductScheduleRedirectTransfer->requireIdProduct()
            ->requireIdProductAbstract();

        $idProductAbstract = $priceProductScheduleRedirectTransfer->getIdProductAbstract();
        $idProductConcrete = $priceProductScheduleRedirectTransfer->getIdProduct();

        $redirectUrl = sprintf(static::REDIRECT_URL_PRODUCT_CONCRETE_PATTERN, $idProductConcrete, $idProductAbstract);

        return $priceProductScheduleRedirectTransfer->setRedirectUrl($redirectUrl);
    }
}
