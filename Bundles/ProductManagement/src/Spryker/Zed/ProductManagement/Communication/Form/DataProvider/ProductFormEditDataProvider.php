<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\PriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;

class ProductFormEditDataProvider extends AbstractProductFormDataProvider
{

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getData($idProductAbstract)
    {
        $formData = $this->getDefaultFormFields();
        $productAbstractTransfer = $this->productManagementFacade->getProductAbstractById($idProductAbstract);

        if ($productAbstractTransfer) {
            $formData = $this->appendGeneralAndSeoData($productAbstractTransfer, $formData);
            $formData = $this->appendPriceAndTax($productAbstractTransfer, $formData);
            $formData = $this->appendAbstractAttributes($productAbstractTransfer, $formData);
            $formData = $this->appendAbstractProductImages($productAbstractTransfer, $formData);
        }

        $formData[ProductFormAdd::FORM_ATTRIBUTE_VARIANT] = [];

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendGeneralAndSeoData(ProductAbstractTransfer $productAbstractTransfer, array $formData)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection();
        $localizedData = $productAbstractTransfer->getLocalizedAttributes();

        $formData[ProductFormAdd::FIELD_SKU] = $productAbstractTransfer->getSku();
        $formData[ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT] = $productAbstractTransfer->getIdProductAbstract();

        foreach ($localizedData as $localizedAttributesTransfer) {
            $localeCode = $localizedAttributesTransfer->getLocale()->getLocaleName();
            $generalFormName = ProductFormAdd::getGeneralFormName($localeCode);
            $seoFormName = ProductFormAdd::getSeoFormName($localeCode);

            //load only data for defined stores/locales
            if (!in_array($localeCode, $localeCollection)) {
                continue;
            }

            $formData[$generalFormName][GeneralForm::FIELD_NAME] = $localizedAttributesTransfer->getName();
            $formData[$generalFormName][GeneralForm::FIELD_DESCRIPTION] = $localizedAttributesTransfer->getDescription();

            $formData[$seoFormName][SeoForm::FIELD_META_TITLE] = $localizedAttributesTransfer->getMetaTitle();
            $formData[$seoFormName][SeoForm::FIELD_META_KEYWORDS] = $localizedAttributesTransfer->getMetaKeywords();
            $formData[$seoFormName][SeoForm::FIELD_META_DESCRIPTION] = $localizedAttributesTransfer->getMetaDescription();
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendPriceAndTax(ProductAbstractTransfer $productAbstractTransfer, array $formData)
    {
        $formData[ProductFormAdd::FORM_PRICE_AND_TAX][PriceForm::FIELD_TAX_RATE] = $productAbstractTransfer->getTaxSetId();

        $priceTransfer = $this->priceFacade->getProductAbstractPrice($productAbstractTransfer->getIdProductAbstract());
        if ($priceTransfer) {
            $formData[ProductFormAdd::FORM_PRICE_AND_TAX][PriceForm::FIELD_PRICE] = $priceTransfer->getPrice();
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendAbstractAttributes(ProductAbstractTransfer $productAbstractTransfer, array $formData)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection(true);
        $abstractAttributesData = $productAbstractTransfer->getLocalizedAttributes();

        foreach ($abstractAttributesData as $localizedAttributesTransfer) {
            $localeCode = $localizedAttributesTransfer->getLocale()->getLocaleName();
            $formName = ProductFormAdd::getAbstractAttributeFormName($localeCode);

            //load only data for defined stores/locales
            if (!in_array($localeCode, $localeCollection)) {
                continue;
            }

            $attributes = $localizedAttributesTransfer->getAttributes();

            foreach ($attributes as $key => $value) {
                $formData[$formName][$key][AttributeAbstractForm::FIELD_NAME] = isset($value);
                $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE] = $value;
                $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID] = null;
            }
        }

        $formName = ProductFormAdd::getAbstractAttributeFormName(ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $attributes = $productAbstractTransfer->getAttributes();

        foreach ($attributes as $key => $value) {
            $formData[$formName][$key][AttributeAbstractForm::FIELD_NAME] = isset($value);
            $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE] = $value;
            $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID] = null;
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendAbstractProductImages(ProductAbstractTransfer $productAbstractTransfer, array $formData)
    {
        $imageSetCollection = $this->getProductImagesForAbstractProduct($productAbstractTransfer->getIdProductAbstract());
        $localeCollection = $this->localeProvider->getLocaleCollection();

        //group by set ids
        $imageDataCollection = [];
        foreach ($localeCollection as $localeCode) {
            $formName = ProductFormAdd::getAbstractImagesFormName($localeCode);

            $imageData = [];
            $setData = [];
            foreach ($imageSetCollection[$localeCode] as $data) {
                $setId = $data[ImageForm::FIELD_SET_ID];
                $imageData[$setId][] = $data;

                $setId = $data[ImageForm::FIELD_SET_ID];
                $item = [];
                $item[ImageForm::FIELD_SET_ID] = $setId;
                $item[ImageForm::FIELD_SET_NAME] = $data[ImageForm::FIELD_SET_NAME];
                $setData[$setId] = $item;
            }

            foreach ($setData as $setId => $data) {
                $setData[$setId][ImageForm::IMAGE_COLLECTION] = $imageData[$setId];
            }

            $imageDataCollection[$formName] = $setData;
        }

        //remove set ids
        $result = [];
        foreach ($imageDataCollection as $formKey => $setFormData) {
            foreach ($setFormData as $setId => $setData) {
                $result[$formKey][] = $setData;
            }

        }

        //assign to form data and overwrite defaults
        foreach ($localeCollection as $localeCode) {
            $formName = ProductFormAdd::getAbstractImagesFormName($localeCode);
            $formData[$formName] = $result[$formName];
        }

        return $formData;
    }

}
