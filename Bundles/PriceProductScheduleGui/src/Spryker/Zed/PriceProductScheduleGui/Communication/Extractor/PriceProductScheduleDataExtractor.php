<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Extractor;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

class PriceProductScheduleDataExtractor implements PriceProductScheduleDataExtractorInterface
{
    protected const TITLE_PRODUCT_ABSTRACT_PATTERN = 'Edit Product Abstract: %s';
    protected const TITLE_PRODUCT_CONCRETE_PATTERN = 'Edit Product Concrete: %s';

    protected const REDIRECT_URL_PRODUCT_CONCRETE_PATTERN = '/product-management/edit/variant?id-product=%s&id-product-abstract=%s#tab-content-scheduled_prices';
    protected const REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN = '/product-management/edit?id-product-abstract=%s#tab-content-scheduled_prices';

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return string
     */
    public function extractTitleFromPriceProductScheduleTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
        if ($idProductAbstract !== null) {
            return sprintf(static::TITLE_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
        }

        $idProductConcrete = $priceProductTransfer->getIdProduct();

        return sprintf(static::TITLE_PRODUCT_CONCRETE_PATTERN, $idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return string
     */
    public function extractRedirectUrlFromPriceProductScheduleTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
        $idProductConcrete = $priceProductTransfer->getIdProduct();

        if ($idProductConcrete !== null) {
            return sprintf(static::REDIRECT_URL_PRODUCT_CONCRETE_PATTERN, $idProductConcrete, $idProductAbstract);
        }

        return sprintf(static::REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
    }
}
