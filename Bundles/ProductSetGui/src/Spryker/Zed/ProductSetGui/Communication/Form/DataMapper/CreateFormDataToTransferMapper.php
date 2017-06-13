<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataMapper;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductSetDataTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Zed\ProductSetGui\Communication\Form\CreateProductSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\General\GeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\General\LocalizedGeneralFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\ImagesFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Images\LocalizedProductImageSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Products\ProductsFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\Seo\SeoFormType;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface;
use Symfony\Component\Form\FormInterface;

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

}
