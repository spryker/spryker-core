<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Reader;

use Symfony\Component\HttpFoundation\Request;

class RequestReader implements RequestReaderInterface
{
    protected const PARAM_ID_PRODUCT = 'idProduct';
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'idProductAbstract';
    protected const TITLE_PRODUCT_ABSTRACT_PATTERN = 'Edit Product Abstract: %s';
    protected const TITLE_PRODUCT_CONCRETE_PATTERN = 'Edit Product Concrete: %s';
    protected const REDIRECT_URL_PRODUCT_CONCRETE_PATTERN = '/product-management/edit/variant?id-product=%s&id-product-abstract=%s#tab-content-scheduled_prices';
    protected const REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN = '/product-management/edit?id-product-abstract=%s#tab-content-scheduled_prices';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getTitleFromRequest(Request $request): string
    {
        $idProductAbstract = $request->query->get(static::PARAM_ID_PRODUCT_ABSTRACT);
        if ($idProductAbstract !== null) {
            return sprintf(static::TITLE_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
        }

        $idProductConcrete = $request->query->get(static::PARAM_ID_PRODUCT);

        return sprintf(static::TITLE_PRODUCT_CONCRETE_PATTERN, $idProductConcrete);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getRedirectUrlFromRequest(Request $request): string
    {
        $idProductAbstract = $request->query->get(static::PARAM_ID_PRODUCT_ABSTRACT);
        $idProductConcrete = $request->query->get(static::PARAM_ID_PRODUCT);

        if ($idProductConcrete !== null) {
            return sprintf(static::REDIRECT_URL_PRODUCT_CONCRETE_PATTERN, $idProductConcrete, $idProductAbstract);
        }

        return sprintf(static::REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function getQueryParamsFromRequest(Request $request): array
    {
        return $request->query->all();
    }
}
