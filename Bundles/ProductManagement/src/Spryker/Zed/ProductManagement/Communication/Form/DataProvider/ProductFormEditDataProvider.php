<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\Product\FormAttributeAbstract;
use Spryker\Zed\ProductManagement\Communication\Form\Product\FormGeneral;
use Spryker\Zed\ProductManagement\Communication\Form\Product\FormPrice;
use Spryker\Zed\ProductManagement\Communication\Form\Product\FormSeo;

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
        $attributeProcessor = $this->getAttributesForAbstractProduct($idProductAbstract);
        $productAbstractTransfer = $this->productManagementFacade->getProductAbstractById($idProductAbstract);

        if ($productAbstractTransfer) {
            $formData = $this->appendGeneralAndSeoData($productAbstractTransfer, $formData);
            $formData = $this->appendPriceAndStock($productAbstractTransfer, $formData);
            $formData = $this->appendAbstractAttributes($productAbstractTransfer, $formData);
        }

        //$attributeValueCollection = $this->convertAbstractLocalizedAttributesToFormValues($attributeProcessor);

        $formData[ProductFormAdd::ATTRIBUTE_VARIANT] = [];

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

            $formData[$generalFormName][FormGeneral::FIELD_NAME] = $localizedAttributesTransfer->getName();
            $formData[$generalFormName][FormGeneral::FIELD_DESCRIPTION] = $localizedAttributesTransfer->getDescription();

            $formData[$seoFormName][FormSeo::FIELD_META_TITLE] = $localizedAttributesTransfer->getMetaTitle();
            $formData[$seoFormName][FormSeo::FIELD_META_KEYWORDS] = $localizedAttributesTransfer->getMetaKeywords();
            $formData[$seoFormName][FormSeo::FIELD_META_DESCRIPTION] = $localizedAttributesTransfer->getMetaDescription();
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendPriceAndStock(ProductAbstractTransfer $productAbstractTransfer, array $formData)
    {
        $formData[ProductFormAdd::PRICE_AND_STOCK][FormPrice::FIELD_TAX_RATE] = $productAbstractTransfer->getTaxSetId();

        $priceTransfer = $this->priceFacade->getProductAbstractPrice($productAbstractTransfer->getIdProductAbstract());
        if ($priceTransfer) {
            $formData[ProductFormAdd::PRICE_AND_STOCK][FormPrice::FIELD_PRICE] = $priceTransfer->getPrice();
            $formData[ProductFormAdd::PRICE_AND_STOCK][FormPrice::FIELD_STOCK] = $productAbstractTransfer->getTaxSetId();
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
                $formData[$formName][$key][FormAttributeAbstract::FIELD_NAME] = isset($value);
                $formData[$formName][$key][FormAttributeAbstract::FIELD_VALUE] = $value;
                $formData[$formName][$key][FormAttributeAbstract::FIELD_VALUE_HIDDEN_ID] = null;
            }
        }

        $formName = ProductFormAdd::getAbstractAttributeFormName(ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $attributes = $productAbstractTransfer->getAttributes();

        foreach ($attributes as $key => $value) {
            $formData[$formName][$key][FormAttributeAbstract::FIELD_NAME] = isset($value);
            $formData[$formName][$key][FormAttributeAbstract::FIELD_VALUE] = $value;
            $formData[$formName][$key][FormAttributeAbstract::FIELD_VALUE_HIDDEN_ID] = null;
        }

        return $formData;
    }

}
