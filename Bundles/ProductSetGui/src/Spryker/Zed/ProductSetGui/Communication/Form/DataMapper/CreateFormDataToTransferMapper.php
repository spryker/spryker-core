<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataMapper;

use Spryker\Zed\ProductSetGui\Communication\Form\CreateProductSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Products\ProductsFormType;

class CreateFormDataToTransferMapper extends AbstractProductSetFormDataToTransferMapper
{
    /**
     * @return string
     */
    protected function getGeneralFormFieldName()
    {
        return CreateProductSetFormType::FIELD_GENERAL_FORM;
    }

    /**
     * @return string
     */
    protected function getProductFormFieldName()
    {
        return CreateProductSetFormType::FIELD_PRODUCTS_FORM;
    }

    /**
     * @return string
     */
    protected function getSeoFormFieldName()
    {
        return CreateProductSetFormType::FIELD_SEO_FORM;
    }

    /**
     * @return string
     */
    protected function getImagesFormFieldName()
    {
        return CreateProductSetFormType::FIELD_IMAGES_FORM;
    }

    /**
     * @return string
     */
    protected function getIdProductAbstractFieldName()
    {
        return ProductsFormType::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS;
    }
}
