<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Everon\Component\Collection\Collection;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Form\BundledProductForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ConcreteGeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormEdit;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductStockHelperInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\ProductManagement\ProductManagementConfig;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class ProductConcreteFormEditDataProvider extends AbstractProductFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Helper\ProductStockHelperInterface
     */
    protected $productStockHelper;

    /**
     * @var \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface[]
     */
    protected $formEditDataProviderExpanderPlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface $productImageFacade
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Generated\Shared\Transfer\LocaleTransfer $currentLocale
     * @param array $attributeCollection
     * @param array $taxCollection
     * @param string $imageUrlPrefix
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface $store
     * @param \Spryker\Zed\ProductManagement\Communication\Helper\ProductStockHelperInterface $productStockHelper
     * @param \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface[] $formEditDataProviderExpanderPlugins
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductQueryContainerInterface $productQueryContainer,
        StockQueryContainerInterface $stockQueryContainer,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToProductImageInterface $productImageFacade,
        LocaleProvider $localeProvider,
        LocaleTransfer $currentLocale,
        array $attributeCollection,
        array $taxCollection,
        $imageUrlPrefix,
        ProductManagementToStoreInterface $store,
        ProductStockHelperInterface $productStockHelper,
        array $formEditDataProviderExpanderPlugins
    ) {
        parent::__construct(
            $categoryQueryContainer,
            $productManagementQueryContainer,
            $productQueryContainer,
            $stockQueryContainer,
            $productFacade,
            $productImageFacade,
            $localeProvider,
            $currentLocale,
            $attributeCollection,
            $taxCollection,
            $imageUrlPrefix,
            $store
        );

        $this->attributeTransferCollection = new Collection($attributeCollection);
        $this->productStockHelper = $productStockHelper;
        $this->formEditDataProviderExpanderPlugins = $formEditDataProviderExpanderPlugins;
    }

    /**
     * @param int|null $idProductAbstract
     * @param string|null $type
     *
     * @return mixed
     */
    public function getOptions($idProductAbstract = null, $type = null)
    {
        $formOptions = parent::getOptions($idProductAbstract);

        $formOptions[ProductConcreteFormEdit::OPTION_IS_BUNDLE_ITEM] = ($type === ProductManagementConfig::PRODUCT_TYPE_BUNDLE) ? true : false;

        return $formOptions;
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        $formData = parent::getDefaultFormFields();

        unset($formData[ProductFormAdd::FORM_PRICE_AND_TAX]);

        $formData[ProductFormAdd::FORM_PRICE_AND_STOCK] = $this->getDefaultStockFields();
        $formData[ProductConcreteFormEdit::FIELD_ID_PRODUCT_CONCRETE] = null;
        $formData[ProductConcreteFormEdit::FIELD_VALID_FROM] = null;
        $formData[ProductConcreteFormEdit::FIELD_VALID_TO] = null;

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
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);
        $productTransfer = $this->productFacade->findProductConcreteById($idProduct);

        if ($productAbstractTransfer) {
            $formData = $this->appendVariantGeneralAndSeoData($productAbstractTransfer, $productTransfer, $formData);
            $formData = $this->appendVariantPriceAndStock($productAbstractTransfer, $productTransfer, $formData);
            $formData = $this->appendConcreteProductImages($productAbstractTransfer, $productTransfer, $formData);
            $formData = $this->appendBundledProducts($productTransfer, $formData);
        }

        foreach ($this->formEditDataProviderExpanderPlugins as $expanderPlugin) {
            $expanderPlugin->expand($productTransfer, $formData);
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendVariantGeneralAndSeoData(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productTransfer, array $formData)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection();
        $localizedData = $productTransfer->getLocalizedAttributes();

        $formData[ProductFormAdd::FIELD_SKU] = $productTransfer->getSku();
        $formData[ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT] = $productAbstractTransfer->getIdProductAbstract();
        $formData[ProductConcreteFormEdit::FIELD_VALID_FROM] = $productTransfer->getValidFrom();
        $formData[ProductConcreteFormEdit::FIELD_VALID_TO] = $productTransfer->getValidTo();

        foreach ($localizedData as $localizedAttributesTransfer) {
            $localeCode = $localizedAttributesTransfer->getLocale()->getLocaleName();
            $generalFormName = ProductFormAdd::getGeneralFormName($localeCode);
            $seoFormName = ProductFormAdd::getSeoFormName($localeCode);

            if (!$this->hasLocale($localeCode, $localeCollection)) {
                continue;
            }

            $formData[$generalFormName][ConcreteGeneralForm::FIELD_NAME] = $localizedAttributesTransfer->getName();
            $formData[$generalFormName][ConcreteGeneralForm::FIELD_DESCRIPTION] = $localizedAttributesTransfer->getDescription();
            $formData[$generalFormName][ConcreteGeneralForm::FIELD_IS_SEARCHABLE] = $localizedAttributesTransfer->getIsSearchable();

            $formData[$seoFormName][SeoForm::FIELD_META_TITLE] = $localizedAttributesTransfer->getMetaTitle();
            $formData[$seoFormName][SeoForm::FIELD_META_KEYWORDS] = $localizedAttributesTransfer->getMetaKeywords();
            $formData[$seoFormName][SeoForm::FIELD_META_DESCRIPTION] = $localizedAttributesTransfer->getMetaDescription();
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendVariantPriceAndStock(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productTransfer, array $formData)
    {
        $formData[ProductFormAdd::FIELD_PRICES] = $productTransfer->getPrices();
        $stockType = $this->stockQueryContainer->queryAllStockTypes()->find()->getData();
        $this->productStockHelper->addMissingStockTypes($productTransfer, $stockType);

        $stockCollection = $productTransfer->getStocks();

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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendBundledProducts(ProductConcreteTransfer $productTransfer, array $formData)
    {
        if ($productTransfer->getProductBundle() === null) {
            return $formData;
        }

        $bundledProducts = $productTransfer->getProductBundle()->getBundledProducts();
        foreach ($bundledProducts as $productForBundleTransfer) {
            $bundledProduct[BundledProductForm::FIELD_QUANTITY] = $productForBundleTransfer->getQuantity();
            $bundledProduct[BundledProductForm::FIELD_ID_PRODUCT_CONCRETE] = $productForBundleTransfer->getIdProductConcrete();
            $bundledProduct[BundledProductForm::FIELD_SKU] = $productForBundleTransfer->getSku();

            $formData[ProductConcreteFormEdit::FORM_ASSIGNED_BUNDLED_PRODUCTS][] = $bundledProduct;
        }

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendVariantAbstractAttributes(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productTransfer, array $formData)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection(true);
        $attributesData = $productTransfer->getLocalizedAttributes();

        foreach ($attributesData as $localizedAttributesTransfer) {
            $localeCode = $localizedAttributesTransfer->getLocale()->getLocaleName();
            $formName = ProductFormAdd::getAbstractAttributeFormName($localeCode);

            if (!$this->hasLocale($localeCode, $localeCollection)) {
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param array $formData
     *
     * @return array
     */
    protected function appendConcreteProductImages(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productTransfer, array $formData)
    {
        return array_merge(
            $formData,
            $this->getProductImagesForConcreteProduct($productTransfer->getIdProductConcrete())
        );
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
