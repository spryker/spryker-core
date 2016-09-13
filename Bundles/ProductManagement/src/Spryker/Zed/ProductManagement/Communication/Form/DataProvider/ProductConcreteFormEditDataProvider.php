<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormEdit;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\PriceForm as ConcretePriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;

class ProductConcreteFormEditDataProvider extends AbstractProductFormDataProvider
{

    /**
     * @param int|null $idProductAbstract
     *
     * @return array
     */
    protected function getDefaultFormFields($idProductAbstract = null)
    {
        $formData = parent::getDefaultFormFields($idProductAbstract);

        unset($formData[ProductFormAdd::FORM_PRICE_AND_TAX]);
        $formData[ProductFormAdd::FORM_PRICE_AND_TAX] = [
            ConcretePriceForm::FIELD_PRICE => 0,
        ];

        $formData[ProductFormAdd::FORM_PRICE_AND_STOCK] = $this->getDefaultStockFields();
        $formData[ProductConcreteFormEdit::FIELD_ID_PRODUCT_CONCRETE] = null;

        return $formData;
    }

    /**
     * @return array
     */
    protected function getDefaultStockFields()
    {
        $result = [];
        $stockTypeCollection = $this->stockQueryContainer->queryAllStockTypes()->find();

        foreach ($stockTypeCollection as $stockTypEntity) {
            $result[] = [
                StockForm::FIELD_HIDDEN_FK_STOCK => $stockTypEntity->getIdStock(),
                StockForm::FIELD_HIDDEN_STOCK_PRODUCT_ID => 0,
                StockForm::FIELD_IS_NEVER_OUT_OF_STOCK => false,
                StockForm::FIELD_TYPE => $stockTypEntity->getName(),
                StockForm::FIELD_QUANTITY => 0,
            ];
        }

        return $result;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProduct
     *
     * @return array
     */
    public function getData($idProductAbstract, $idProduct)
    {
        $formData = $this->getDefaultFormFields();
        $productAbstractTransfer = $this->productManagementFacade->getProductAbstractById($idProductAbstract);
        $productTransfer = $this->productManagementFacade->getProductConcreteById($idProduct);

        if ($productAbstractTransfer) {
            $formData = $this->appendVariantGeneralAndSeoData($productAbstractTransfer, $productTransfer, $formData);
            $formData = $this->appendVariantPriceAndStock($productAbstractTransfer, $productTransfer, $formData);
            $formData = $this->appendVariantAbstractAttributes($productAbstractTransfer, $productTransfer, $formData);
            $formData = $this->appendConcreteProductImages($productAbstractTransfer, $productTransfer, $formData);
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendVariantGeneralAndSeoData(ProductAbstractTransfer $productAbstractTransfer, ZedProductConcreteTransfer $productTransfer, array $formData)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection();
        $localizedData = $productTransfer->getLocalizedAttributes();

        $formData[ProductFormAdd::FIELD_SKU] = $productTransfer->getSku();
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
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendVariantPriceAndStock(ProductAbstractTransfer $productAbstractTransfer, ZedProductConcreteTransfer $productTransfer, array $formData)
    {
        $priceTransfer = $this->priceFacade->getProductConcretePrice($productTransfer->getIdProductConcrete());
        if ($priceTransfer) {
            $formData[ProductFormAdd::FORM_PRICE_AND_TAX][ConcretePriceForm::FIELD_PRICE] = $priceTransfer->getPrice();
        }

        $stockCollection = $productTransfer->getStock();

        if (count($stockCollection)) {
            $formData[ProductFormAdd::FORM_PRICE_AND_STOCK] = [];
        }

        foreach ($stockCollection as $stockTransfer) {
            $stock[StockForm::FIELD_HIDDEN_FK_STOCK] = $stockTransfer->getFkStock();
            $stock[StockForm::FIELD_HIDDEN_STOCK_PRODUCT_ID] = $stockTransfer->getIdStockProduct();
            $stock[StockForm::FIELD_IS_NEVER_OUT_OF_STOCK] = (bool)$stockTransfer->getIsNeverOutOfStock();
            $stock[StockForm::FIELD_TYPE] = $stockTransfer->getStockType();
            $stock[StockForm::FIELD_QUANTITY] = $stockTransfer->getQuantity();

            $formData[ProductFormAdd::FORM_PRICE_AND_STOCK][] = $stock;
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendVariantAbstractAttributes(ProductAbstractTransfer $productAbstractTransfer, ZedProductConcreteTransfer $productTransfer, array $formData)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection(true);
        $attributesData = $productTransfer->getLocalizedAttributes();

        foreach ($attributesData as $localizedAttributesTransfer) {
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
        $attributes = $productTransfer->getAttributes();

        foreach ($attributes as $key => $value) {
            $formData[$formName][$key][AttributeAbstractForm::FIELD_NAME] = isset($value);
            $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE] = $value;
            $formData[$formName][$key][AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID] = null;
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendConcreteProductImages(ProductAbstractTransfer $productAbstractTransfer, ZedProductConcreteTransfer $productTransfer, array $formData)
    {
        return array_merge(
            $formData,
            $this->getProductImagesForConcreteProduct($productTransfer->getIdProductConcrete())
        );
    }

}
