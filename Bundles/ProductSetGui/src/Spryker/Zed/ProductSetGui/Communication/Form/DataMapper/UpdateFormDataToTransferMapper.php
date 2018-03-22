<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataMapper;

use Spryker\Zed\ProductSetGui\Communication\Form\Products\UpdateProductsFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\UpdateProductSetFormType;

class UpdateFormDataToTransferMapper extends AbstractProductSetFormDataToTransferMapper
{
    /**
     * @return string
     */
    protected function getGeneralFormFieldName()
    {
        return UpdateProductSetFormType::FIELD_GENERAL_FORM;
    }

    /**
     * @return string
     */
    protected function getProductFormFieldName()
    {
        return UpdateProductSetFormType::FIELD_PRODUCTS_FORM;
    }

    /**
     * @return string
     */
    protected function getSeoFormFieldName()
    {
        return UpdateProductSetFormType::FIELD_SEO_FORM;
    }

    /**
     * @return string
     */
    protected function getImagesFormFieldName()
    {
        return UpdateProductSetFormType::FIELD_IMAGES_FORM;
    }

    /**
     * @return string
     */
    protected function getIdProductAbstractFieldName()
    {
        return UpdateProductsFormType::FIELD_ID_PRODUCT_ABSTRACTS;
    }
}
