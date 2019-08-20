<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Formatter;

use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductScheduleDataFormatter implements PriceProductScheduleDataFormatterInterface
{
    protected const TITLE_PRODUCT_ABSTRACT_PATTERN = 'Edit Product Abstract: %s';
    protected const TITLE_PRODUCT_CONCRETE_PATTERN = 'Edit Product Concrete: %s';

    protected const REDIRECT_URL_PRODUCT_CONCRETE_PATTERN = '/product-management/edit/variant?id-product=%s&id-product-abstract=%s#tab-content-scheduled_prices';
    protected const REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN = '/product-management/edit?id-product-abstract=%s#tab-content-scheduled_prices';

    protected const TIMEZONE_TEXT_PATTERN = 'The timezone used for the scheduled price will be <b>%s</b> as defined on the store selected';

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function formatTitle(PriceProductTransfer $priceProductTransfer): string
    {
        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
        if ($idProductAbstract !== null) {
            return sprintf(static::TITLE_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
        }

        $idProductConcrete = $priceProductTransfer->getIdProduct();

        return sprintf(static::TITLE_PRODUCT_CONCRETE_PATTERN, $idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function formatRedirectUrl(PriceProductTransfer $priceProductTransfer): string
    {
        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
        $idProductConcrete = $priceProductTransfer->getIdProduct();

        if ($idProductConcrete !== null) {
            return sprintf(static::REDIRECT_URL_PRODUCT_CONCRETE_PATTERN, $idProductConcrete, $idProductAbstract);
        }

        return sprintf(static::REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
    }

    /**
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatTimezoneText(?string $timezone): string
    {
        if ($timezone === null) {
            return '';
        }

        return sprintf(static::TIMEZONE_TEXT_PATTERN, $timezone);
    }
}
