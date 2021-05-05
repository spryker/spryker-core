<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use Symfony\Component\HttpFoundation\Request;

class CreateProductAbstractWithSingleConcreteFormDataProvider implements CreateProductAbstractWithSingleConcreteFormDataProviderInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_NAME
     */
    protected const FIELD_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_SKU
     */
    protected const FIELD_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_NAME
     */
    protected const FIELD_CONCRETE_NAME = 'concreteName';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_SKU
     */
    protected const FIELD_CONCRETE_SKU = 'concreteSku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_AUTOGENERATE_SKU
     */
    protected const FIELD_AUTOGENERATE_SKU = 'autogenerateSku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_USE_ABSTRACT_PRODUCT_NAME
     */
    protected const FIELD_USE_ABSTRACT_PRODUCT_NAME = 'useAbstractProductName';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::REQUEST_PARAM_NAME
     */
    protected const REQUEST_PARAM_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::REQUEST_PARAM_NAME
     */
    protected const REQUEST_PARAM_SKU = 'sku';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed[]
     */
    public function getDefaultData(Request $request): array
    {
        $abstractProductName = $request->get(static::REQUEST_PARAM_NAME);
        $abstractProductSku = $request->get(static::REQUEST_PARAM_SKU);

        return [
            static::FIELD_NAME => $abstractProductName,
            static::FIELD_SKU => $abstractProductSku,
            static::FIELD_CONCRETE_NAME => $abstractProductName,
            static::FIELD_CONCRETE_SKU => $abstractProductSku,
            static::FIELD_AUTOGENERATE_SKU => true,
            static::FIELD_USE_ABSTRACT_PRODUCT_NAME => true,
        ];
    }
}
