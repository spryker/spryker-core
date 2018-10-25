<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Everon\Component\Collection\Collection;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormAdd;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface;
use Spryker\Zed\ProductManagement\ProductManagementConfig;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class ProductConcreteFormAddDataProvider
{
    public const DEFAULT_INPUT_TYPE = 'text';
    public const TEXT_AREA_INPUT_TYPE = 'textarea';

    protected const FORM_FIELD_ID = 'id';
    protected const FORM_FIELD_VALUE = 'value';
    protected const FORM_FIELD_NAME = 'name';
    protected const FORM_FIELD_PRODUCT_SPECIFIC = 'product_specific';
    protected const FORM_FIELD_LABEL = 'label';
    protected const FORM_FIELD_SUPER = 'super';
    protected const FORM_FIELD_INPUT_TYPE = 'input_type';
    protected const FORM_FIELD_VALUE_DISABLED = 'value_disabled';
    protected const FORM_FIELD_NAME_DISABLED = 'name_disabled';
    protected const FORM_FIELD_ALLOW_INPUT = 'allow_input';

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    protected $stockQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $currentLocale;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface
     */
    protected $productAttributeFacade;

    /**
     * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]|\Everon\Component\Collection\CollectionInterface
     */
    protected $attributeTransferCollection;

    /**
     * @var array
     */
    protected $taxCollection = [];

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface
     */
    protected $store;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Generated\Shared\Transfer\LocaleTransfer $currentLocale
     * @param array $attributeCollection
     * @param array $taxCollection
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface $store
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface $productAttributeFacade
     */
    public function __construct(
        StockQueryContainerInterface $stockQueryContainer,
        ProductManagementToProductInterface $productFacade,
        LocaleProvider $localeProvider,
        LocaleTransfer $currentLocale,
        array $attributeCollection,
        array $taxCollection,
        ProductManagementToStoreInterface $store,
        ProductManagementToProductAttributeInterface $productAttributeFacade
    ) {
        $this->attributeTransferCollection = new Collection($attributeCollection);
        $this->stockQueryContainer = $stockQueryContainer;
        $this->productFacade = $productFacade;
        $this->localeProvider = $localeProvider;
        $this->currentLocale = $currentLocale;
        $this->taxCollection = $taxCollection;
        $this->store = $store;
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param int|null $idProductAbstract
     * @param string|null $type
     *
     * @return mixed
     */
    public function getOptions($idProductAbstract = null, $type = null)
    {
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $localeCollection = $this->localeProvider->getLocaleCollection();
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        $localizedAttributeOptions = [];

        foreach ($localeCollection as $localeTransfer) {
            $localizedAttributeOptions[$localeTransfer->getLocaleName()] = $this->convertAbstractLocalizedAttributesToFormOptions($productAbstractTransfer, $localeTransfer);
        }

        $localizedAttributeOptions[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE] = $this->convertAbstractLocalizedAttributesToFormOptions($productAbstractTransfer, null);

        $formOptions[ProductConcreteFormAdd::OPTION_ATTRIBUTE_ABSTRACT] = $localizedAttributeOptions;
        $formOptions[ProductConcreteFormAdd::OPTION_ID_LOCALE] = $this->currentLocale->getIdLocale();
        $formOptions[ProductConcreteFormAdd::OPTION_ATTRIBUTE_SUPER] = [];
        $formOptions[ProductConcreteFormAdd::OPTION_TAX_RATES] = $this->taxCollection;

        if ($this->store->getCurrencyIsoCode()) {
            $formOptions[ProductConcreteFormAdd::OPTION_CURRENCY_ISO_CODE] = $this->store->getCurrencyIsoCode();
        }
        $formOptions[ProductConcreteFormAdd::OPTION_ID_PRODUCT_ABSTRACT] = $idProductAbstract;
        $formOptions[ProductConcreteFormAdd::OPTION_IS_BUNDLE_ITEM] = $type === ProductManagementConfig::PRODUCT_TYPE_BUNDLE;
        $formOptions[ProductConcreteFormAdd::OPTION_SUPER_ATTRIBUTES] = $this->getSuperAttributesOption($productAbstractTransfer);

        return $formOptions;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            ProductConcreteFormAdd::FIELD_ID_PRODUCT_ABSTRACT => null,
            ProductConcreteFormAdd::FIELD_SKU => null,
            ProductConcreteFormAdd::FORM_PRICE_AND_STOCK => $this->getDefaultStockFields(),
        ];

        $data = array_merge($data, $this->getGeneralAttributesDefaultFields());
        $data = array_merge($data, $this->getSeoDefaultFields());
        $data = array_merge($data, $this->getImagesDefaultFields());

        $data[ProductConcreteFormAdd::FORM_PRICE_AND_STOCK] = $this->getDefaultStockFields();

        return $data;
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function getSuperAttributesOption(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productConcreteTransfers = $this->productFacade->getConcreteProductsByAbstractProductId($productAbstractTransfer->getIdProductAbstract());

        return $this->productAttributeFacade->getUniqueSuperAttributesFromConcreteProducts($productConcreteTransfers);
    }

    /**
     * @return array
     */
    protected function getGeneralAttributesDefaultFields()
    {
        $availableLocales = $this->localeProvider->getLocaleCollection();

        $result = [];
        foreach ($availableLocales as $localeTransfer) {
            $key = ProductConcreteFormAdd::getGeneralFormName($localeTransfer->getLocaleName());
            $result[$key] = [
                GeneralForm::FIELD_NAME => null,
                GeneralForm::FIELD_DESCRIPTION => null,
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getSeoDefaultFields()
    {
        $availableLocales = $this->localeProvider->getLocaleCollection();

        $result = [];
        foreach ($availableLocales as $localeTransfer) {
            $key = ProductConcreteFormAdd::getSeoFormName($localeTransfer->getLocaleName());
            $result[$key] = [
                SeoForm::FIELD_META_TITLE => null,
                SeoForm::FIELD_META_KEYWORDS => null,
                SeoForm::FIELD_META_DESCRIPTION => null,
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getImagesDefaultFields()
    {
        $availableLocales = $this->localeProvider->getLocaleCollection();
        $data = $this->getImageFields();

        $result = [];
        foreach ($availableLocales as $localeTransfer) {
            $key = ProductConcreteFormAdd::getImagesFormName($localeTransfer->getLocaleName());
            $result[$key] = [$data];
        }

        $defaultKey = ProductConcreteFormAdd::getLocalizedPrefixName(ProductConcreteFormAdd::FORM_IMAGE_SET, ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $result[$defaultKey] = [$data];

        return $result;
    }

    /**
     * @return array
     */
    protected function getImageFields()
    {
        return [
            ImageSetForm::FIELD_SET_ID => null,
            ImageSetForm::FIELD_SET_NAME => null,
            ImageSetForm::PRODUCT_IMAGES => [
                [
                    ImageCollectionForm::FIELD_ID_PRODUCT_IMAGE => null,
                    ImageCollectionForm::FIELD_IMAGE_PREVIEW => null,
                    ImageCollectionForm::FIELD_IMAGE_PREVIEW_LARGE_URL => null,
                    ImageCollectionForm::FIELD_FK_IMAGE_SET_ID => null,
                    ImageCollectionForm::FIELD_IMAGE_SMALL => null,
                    ImageCollectionForm::FIELD_IMAGE_LARGE => null,
                    ImageCollectionForm::FIELD_SORT_ORDER => null,
                    ImageSetForm::FIELD_SET_FK_LOCALE => null,
                    ImageSetForm::FIELD_SET_FK_PRODUCT => null,
                    ImageSetForm::FIELD_SET_FK_PRODUCT_ABSTRACT => null,
                ],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    protected function convertAbstractLocalizedAttributesToFormOptions(
        ?ProductAbstractTransfer $productAbstractTransfer = null,
        ?LocaleTransfer $localeTransfer = null
    ): array {
        $values = $this->getAttributeTransferCollectionValues();

        if (!$productAbstractTransfer) {
            return $values;
        }

        $productAttributeValues = [];

        if ($localeTransfer) {
            foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributeTransfer) {
                if ($localizedAttributeTransfer->getLocale()->getLocaleName() !== $localeTransfer->getLocaleName()) {
                    continue;
                }

                $productAttributeValues = $localizedAttributeTransfer->getAttributes();
            }
        } else {
            $productAttributeValues = $productAbstractTransfer->getAttributes();
        }

        $productAttributeKeys = $this->productFacade->getCombinedAbstractAttributeKeys($productAbstractTransfer, $localeTransfer);

        return $this->getproductAttributeKeysValues($values, $productAttributeKeys, $productAttributeValues);
    }

    /**
     * @param string $keyToLocalize
     *
     * @return string
     */
    protected function getLocalizedAttributeMetadataKey($keyToLocalize)
    {
        if (!$this->attributeTransferCollection->has($keyToLocalize)) {
            return $keyToLocalize;
        }

        return $this->attributeTransferCollection->get($keyToLocalize)->getKey();
    }

    /**
     * @return array
     */
    protected function getAttributeTransferCollectionValues(): array
    {
        $values = [];

        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $values[$type] = [
                static::FORM_FIELD_ID => $attributeTransfer->getIdProductManagementAttribute(),
                static::FORM_FIELD_VALUE => null,
                static::FORM_FIELD_NAME => false,
                static::FORM_FIELD_PRODUCT_SPECIFIC => false,
                static::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                static::FORM_FIELD_SUPER => $attributeTransfer->getIsSuper(),
                static::FORM_FIELD_INPUT_TYPE => $attributeTransfer->getInputType(),
                static::FORM_FIELD_VALUE_DISABLED => true,
                static::FORM_FIELD_NAME_DISABLED => false,
                static::FORM_FIELD_ALLOW_INPUT => $attributeTransfer->getAllowInput(),
            ];
        }

        return $values;
    }

    /**
     * @param array $values
     * @param array $productAttributeKeys
     * @param array $productAttributeValues
     *
     * @return array
     */
    protected function getproductAttributeKeysValues(array $values, array $productAttributeKeys, array $productAttributeValues): array
    {
        foreach ($productAttributeKeys as $type) {
            $isDefined = $this->attributeTransferCollection->has($type);

            if ($isDefined) {
                continue;
            }

            $inputType = static::DEFAULT_INPUT_TYPE;
            $value = isset($productAttributeValues[$type]) ? $productAttributeValues[$type] : null;
            $shouldBeTextArea = mb_strlen($value) > 255;

            if ($shouldBeTextArea) {
                $inputType = static::TEXT_AREA_INPUT_TYPE;
            }

            $values[$type] = [
                static::FORM_FIELD_ID => null,
                static::FORM_FIELD_VALUE => $value,
                static::FORM_FIELD_NAME => isset($value),
                static::FORM_FIELD_PRODUCT_SPECIFIC => true,
                static::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                static::FORM_FIELD_SUPER => false,
                static::FORM_FIELD_INPUT_TYPE => $inputType,
                static::FORM_FIELD_VALUE_DISABLED => true,
                static::FORM_FIELD_NAME_DISABLED => true,
                static::FORM_FIELD_ALLOW_INPUT => false,
            ];
        }

        return $values;
    }
}
