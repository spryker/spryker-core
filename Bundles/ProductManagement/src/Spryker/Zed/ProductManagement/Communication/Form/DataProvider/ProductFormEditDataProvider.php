<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;

class ProductFormEditDataProvider extends AbstractProductFormDataProvider
{
    /**
     * @param int $idProductAbstract
     * @param array|null $priceDimension
     *
     * @return array
     */
    public function getData($idProductAbstract, ?array $priceDimension = null)
    {
        $formData = $this->getDefaultFormFields($priceDimension);
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        if ($productAbstractTransfer) {
            $formData = $this->appendGeneralAndSeoData($productAbstractTransfer, $formData);
            $formData = $this->appendPriceAndTax($productAbstractTransfer, $formData);
            $formData = $this->appendAbstractProductImages($productAbstractTransfer, $formData);
            $formData = $this->appendStoreRelation($productAbstractTransfer, $formData);

            $formData[ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT] = $productAbstractTransfer->getIdProductAbstract();
        }

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
        $formData[ProductFormAdd::FIELD_NEW_FROM] = $productAbstractTransfer->getNewFrom();
        $formData[ProductFormAdd::FIELD_NEW_TO] = $productAbstractTransfer->getNewTo();

        foreach ($localizedData as $localizedAttributesTransfer) {
            $localeCode = $localizedAttributesTransfer->getLocale()->getLocaleName();
            $generalFormName = ProductFormAdd::getGeneralFormName($localeCode);
            $seoFormName = ProductFormAdd::getSeoFormName($localeCode);

            if (!$this->hasLocale($localeCode, $localeCollection)) {
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
        $formData[ProductFormAdd::FIELD_TAX_RATE] = $productAbstractTransfer->getIdTaxSet();
        $formData[ProductFormAdd::FIELD_PRICES] = $this->getProductAbstractPricesByPriceDimension(
            $productAbstractTransfer,
            $formData
        );

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
        $abstractLocalizedAttributesData = $productAbstractTransfer->getLocalizedAttributes();

        foreach ($abstractLocalizedAttributesData as $localizedAttributesTransfer) {
            $localeCode = $localizedAttributesTransfer->getLocale()->getLocaleName();
            $formName = ProductFormAdd::getAbstractAttributeFormName($localeCode);

            if (!$this->hasLocale($localeCode, $localeCollection)) {
                continue;
            }

            $attributes = $localizedAttributesTransfer->getAttributes();
            foreach ($attributes as $key => $value) {
                $id = null;
                $attributeTransfer = $this->attributeTransferCollection->get($key);
                if ($attributeTransfer) {
                    $id = $attributeTransfer->getIdProductManagementAttribute();
                }

                $formData[$formName][$key][AttributeAbstractForm::FIELD_NAME] = isset($value);
                $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE] = $value;
                $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID] = $id;
            }
        }

        $formName = ProductFormAdd::getAbstractAttributeFormName(ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $attributes = $productAbstractTransfer->getAttributes();

        foreach ($attributes as $key => $value) {
            $id = null;
            $attributeTransfer = $this->attributeTransferCollection->get($key);
            if ($attributeTransfer) {
                $id = $attributeTransfer->getIdProductManagementAttribute();
            }

            $formData[$formName][$key][AttributeAbstractForm::FIELD_NAME] = isset($value);
            $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE] = $value;
            $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID] = $id;
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
        $imageData = $this->getProductImagesForAbstractProduct($productAbstractTransfer->getIdProductAbstract());

        $result = $formData;
        foreach ($formData as $name => $data) {
            if (array_key_exists($name, $imageData)) {
                $result[$name] = $imageData[$name];
            }
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendStoreRelation(ProductAbstractTransfer $productAbstractTransfer, array $formData)
    {
        $formData[ProductFormAdd::FORM_STORE_RELATION] = $productAbstractTransfer->getStoreRelation();

        return $formData;
    }

    /**
     * @param string $localeCode
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return bool
     */
    protected function hasLocale($localeCode, array $localeCollection)
    {
        foreach ($localeCollection as $localeTransfer) {
            if ($localeTransfer->getLocaleName() === $localeCode) {
                return true;
            }
        }

        return false;
    }
}
