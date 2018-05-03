<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Everon\Component\Collection\Collection;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class AbstractProductFormDataProvider
{
    const LOCALE_NAME = 'locale_name';

    const FORM_FIELD_ID = 'id';
    const FORM_FIELD_VALUE = 'value';
    const FORM_FIELD_NAME = 'name';
    const FORM_FIELD_PRODUCT_SPECIFIC = 'product_specific';
    const FORM_FIELD_LABEL = 'label';
    const FORM_FIELD_SUPER = 'super';
    const FORM_FIELD_INPUT_TYPE = 'input_type';
    const FORM_FIELD_VALUE_DISABLED = 'value_disabled';
    const FORM_FIELD_NAME_DISABLED = 'name_disabled';
    const FORM_FIELD_ALLOW_INPUT = 'allow_input';

    const IMAGES = 'images';

    const DEFAULT_INPUT_TYPE = 'text';
    const TEXT_AREA_INPUT_TYPE = 'textarea';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

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
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]|\Everon\Component\Collection\CollectionInterface
     */
    protected $attributeTransferCollection;

    /**
     * @var array
     */
    protected $taxCollection = [];

    /**
     * @var string
     */
    protected $imageUrlPrefix;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface|null
     */
    protected $store;

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
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface|null $store
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
        ?ProductManagementToStoreInterface $store = null
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->productQueryContainer = $productQueryContainer;
        $this->stockQueryContainer = $stockQueryContainer;
        $this->productImageFacade = $productImageFacade;
        $this->localeProvider = $localeProvider;
        $this->productFacade = $productFacade;
        $this->currentLocale = $currentLocale;
        $this->attributeTransferCollection = new Collection($attributeCollection);
        $this->taxCollection = $taxCollection;
        $this->imageUrlPrefix = $imageUrlPrefix;
        $this->store = $store;
    }

    /**
     * @param int|null $idProductAbstract
     *
     * @return mixed
     */
    public function getOptions($idProductAbstract = null)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection();

        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        $localizedAttributeOptions = [];
        foreach ($localeCollection as $localeTransfer) {
            $localizedAttributeOptions[$localeTransfer->getLocaleName()] = $this->convertAbstractLocalizedAttributesToFormOptions($productAbstractTransfer, $localeTransfer);
        }
        $localizedAttributeOptions[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE] = $this->convertAbstractLocalizedAttributesToFormOptions($productAbstractTransfer, null);

        $formOptions[ProductFormAdd::OPTION_ATTRIBUTE_SUPER] = $this->convertVariantAttributesToFormOptions($productAbstractTransfer);
        $formOptions[ProductFormAdd::OPTION_ATTRIBUTE_ABSTRACT] = $localizedAttributeOptions;

        $formOptions[ProductFormAdd::OPTION_ID_LOCALE] = $this->currentLocale->getIdLocale();
        $formOptions[ProductFormAdd::OPTION_TAX_RATES] = $this->taxCollection;

        if ($this->store) {
            $formOptions[ProductFormAdd::OPTION_CURRENCY_ISO_CODE] = $this->store->getCurrencyIsoCode();
        }

        return $formOptions;
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        $data = [
            ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT => null,
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::FORM_ATTRIBUTE_SUPER => $this->getAttributeVariantDefaultFields(),
        ];

        $data = array_merge($data, $this->getGeneralAttributesDefaultFields());
        $data = array_merge($data, $this->getSeoDefaultFields());
        $data = array_merge($data, $this->getImagesDefaultFields());

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapMoneyTransfer(CurrencyTransfer $currencyTransfer, ?StoreTransfer $storeTransfer = null)
    {
        $moneyValueTransfer = new MoneyValueTransfer();
        $moneyValueTransfer->setCurrency($currencyTransfer);
        $moneyValueTransfer->setFkCurrency($currencyTransfer->getIdCurrency());

        if ($storeTransfer) {
            $moneyValueTransfer->setFkStore($storeTransfer->getIdStore());
        }

        return $moneyValueTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function getProductImagesForAbstractProduct($idProductAbstract)
    {
        $imageSetTransferCollection = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId($idProductAbstract);
        return $this->getProductImageSetCollection($imageSetTransferCollection);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    protected function getProductImagesForConcreteProduct($idProduct)
    {
        $imageSetTransferCollection = $this->productImageFacade->getProductImagesSetCollectionByProductId($idProduct);
        return $this->getProductImageSetCollection($imageSetTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $imageSetTransferCollection
     *
     * @return array
     */
    protected function getProductImageSetCollection($imageSetTransferCollection)
    {
        $result = $this->getImagesDefaultFields();

        if (!$imageSetTransferCollection) {
            return $result;
        }

        $defaults = [];
        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $data = [];
            foreach ($imageSetTransferCollection as $imageSetTransfer) {
                if ($imageSetTransfer->getLocale() === null) {
                    $defaults[$imageSetTransfer->getIdProductImageSet()] = $this->convertProductImageSet($imageSetTransfer);
                    continue;
                }

                $fkLocale = (int)$imageSetTransfer->getLocale()->getIdLocale();
                if ($fkLocale !== (int)$localeTransfer->getIdLocale()) {
                    continue;
                }

                $data[$imageSetTransfer->getIdProductImageSet()] = $this->convertProductImageSet($imageSetTransfer);
            }

            $formName = ProductFormAdd::getImagesFormName($localeTransfer->getLocaleName());
            $result[$formName] = array_values($data);
        }

        $defaultName = ProductFormAdd::getLocalizedPrefixName(ProductFormAdd::FORM_IMAGE_SET, ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $result[$defaultName] = array_values($defaults);

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $imageSetTransfer
     *
     * @return array
     */
    protected function convertProductImageSet(ProductImageSetTransfer $imageSetTransfer)
    {
        $item = $imageSetTransfer->toArray();
        $itemImages = [];

        foreach ($imageSetTransfer->getProductImages() as $imageTransfer) {
            $image = $imageTransfer->toArray();
            $image[ImageCollectionForm::FIELD_IMAGE_PREVIEW] = $this->getImageUrl($image[ImageCollectionForm::FIELD_IMAGE_SMALL]);
            $image[ImageCollectionForm::FIELD_IMAGE_PREVIEW_LARGE_URL] = $this->getImageUrl($image[ImageCollectionForm::FIELD_IMAGE_LARGE]);
            $image[ImageCollectionForm::FIELD_FK_IMAGE_SET_ID] = $imageSetTransfer->getIdProductImageSet();
            $itemImages[] = $image;
        }

        $item[ImageSetForm::PRODUCT_IMAGES] = $itemImages;

        return $item;
    }

    /**
     * @return array
     */
    protected function getGeneralAttributesDefaultFields()
    {
        $availableLocales = $this->localeProvider->getLocaleCollection();

        $result = [];
        foreach ($availableLocales as $localeTransfer) {
            $key = ProductFormAdd::getGeneralFormName($localeTransfer->getLocaleName());
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
            $key = ProductFormAdd::getSeoFormName($localeTransfer->getLocaleName());
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
    protected function getAttributeVariantDefaultFields()
    {
        return $this->convertVariantAttributesToFormValues(true);
    }

    /**
     * @return array
     */
    protected function getAttributeAbstractDefaultFields()
    {
        $availableLocales = $this->localeProvider->getLocaleCollection();

        $result = [];
        foreach ($availableLocales as $localeTransfer) {
            $key = ProductFormAdd::getAbstractAttributeFormName($localeTransfer->getLocaleName());
            $result[$key] = $this->convertAbstractLocalizedAttributesToFormValues(true);
        }

        $defaultKey = ProductFormAdd::getLocalizedPrefixName(ProductFormAdd::FORM_ATTRIBUTE_ABSTRACT, ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $result[$defaultKey] = $this->convertAbstractLocalizedAttributesToFormValues(true);

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
            $key = ProductFormAdd::getImagesFormName($localeTransfer->getLocaleName());
            $result[$key] = [$data];
        }

        $defaultKey = ProductFormAdd::getLocalizedPrefixName(ProductFormAdd::FORM_IMAGE_SET, ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
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
     * @return array
     */
    protected function getPriceAndStockDefaultFields()
    {
        return $this->convertToFormValues($this->taxCollection);
    }

    /**
     * @param array $data
     * @param array $values
     * @param bool $defaultValue
     *
     * @return array
     */
    protected function convertToFormValues(array $data, array $values = [], $defaultValue = true)
    {
        $attributes = [];
        foreach ($data as $type => $valueSet) {
            $attributes[$type]['value'] = $defaultValue;
            if (isset($values[$type])) {
                $attributes[$type]['value'] = $values[$type];
            }
        }

        return $attributes;
    }

    /**
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertAbstractLocalizedAttributesToFormValues($isNew = false)
    {
        $values = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $attributeValue = isset($attributes[$type]) ? $attributes[$type] : null;

            if ($isNew) {
                $attributeValue = null;
            }

            $values[$type] = [
                AttributeAbstractForm::FIELD_NAME => isset($attributeValue),
                AttributeAbstractForm::FIELD_VALUE => $attributeValue,
                AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID => $attributeTransfer->getIdProductManagementAttribute(),
            ];
        }

        return $values;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    protected function convertAbstractLocalizedAttributesToFormOptions(?ProductAbstractTransfer $productAbstractTransfer = null, ?LocaleTransfer $localeTransfer = null)
    {
        $values = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $isProductSpecificAttribute = false;
            $id = $attributeTransfer->getIdProductManagementAttribute();
            $isSuper = $attributeTransfer->getIsSuper();
            $inputType = $attributeTransfer->getInputType();
            $allowInput = $attributeTransfer->getAllowInput();
            $value = isset($productAttributeValues[$type]) ? $productAttributeValues[$type] : null;
            $checkboxDisabled = false;
            $valueDisabled = true;

            $values[$type] = [
                self::FORM_FIELD_ID => $id,
                self::FORM_FIELD_VALUE => $value,
                self::FORM_FIELD_NAME => isset($value),
                self::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                self::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                self::FORM_FIELD_SUPER => $isSuper,
                self::FORM_FIELD_INPUT_TYPE => $inputType,
                self::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                self::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                self::FORM_FIELD_ALLOW_INPUT => $allowInput,
            ];
        }

        $productAttributeValues = [];
        $productAttributeKeys = [];
        if ($productAbstractTransfer) {
            if ($localeTransfer) {
                foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributeTransfer) {
                    if ($localizedAttributeTransfer->getLocale()->getLocaleName() === $localeTransfer->getLocaleName()) {
                        $productAttributeValues = $localizedAttributeTransfer->getAttributes();
                    }
                }
            } else {
                $productAttributeValues = $productAbstractTransfer->getAttributes();
            }

            $productAttributeKeys = $this->productFacade->getCombinedAbstractAttributeKeys($productAbstractTransfer, $localeTransfer);
        }

        foreach ($productAttributeKeys as $type) {
            $isDefined = $this->attributeTransferCollection->has($type);
            if ($isDefined) {
                continue;
            }
            
            $isProductSpecificAttribute = true;
            $id = null;
            $isSuper = false;
            $inputType = self::DEFAULT_INPUT_TYPE;
            $allowInput = false;
            $value = isset($productAttributeValues[$type]) ? $productAttributeValues[$type] : null;
            $shouldBeTextArea = mb_strlen($value) > 255;
            $checkboxDisabled = true;
            $valueDisabled = true;

            if ($shouldBeTextArea) {
                $inputType = self::TEXT_AREA_INPUT_TYPE;
            }

            $values[$type] = [
                self::FORM_FIELD_ID => $id,
                self::FORM_FIELD_VALUE => $value,
                self::FORM_FIELD_NAME => isset($value),
                self::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                self::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                self::FORM_FIELD_SUPER => $isSuper,
                self::FORM_FIELD_INPUT_TYPE => $inputType,
                self::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                self::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                self::FORM_FIELD_ALLOW_INPUT => $allowInput,
            ];
        }

        return $values;
    }

    /**
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertVariantAttributesToFormValues($isNew = false)
    {
        $productAttributes = [];

        $result = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            if (!$attributeTransfer->getIsSuper()) {
                continue;
            }

            $value = isset($productAttributes[$type]) ? $productAttributes[$type] : null;

            if ($isNew) {
                $value = null;
            }

            $result[$type] = [
                AttributeAbstractForm::FIELD_NAME => null,
                AttributeAbstractForm::FIELD_VALUE => $value,
                AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID => $attributeTransfer->getIdProductManagementAttribute(),
            ];
        }

        $productValues = $this->getProductAttributesFormValues($productAttributes);

        return array_merge($productValues, $result);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstractTransfer
     *
     * @return array
     */
    protected function convertVariantAttributesToFormOptions(?ProductAbstractTransfer $productAbstractTransfer = null)
    {
        $productAttributeKeys = [];
        if ($productAbstractTransfer) {
            foreach ($this->localeProvider->getLocaleCollection() as $localeTransfer) {
                $productAttributeKeys = array_unique(array_merge(
                    $productAttributeKeys,
                    $this->productFacade->getCombinedAbstractAttributeKeys($productAbstractTransfer, $localeTransfer)
                ));
            }
        }

        $productAttributeValues = [];

        $values = [];
        foreach ($productAttributeKeys as $type) {
            $isDefined = $this->attributeTransferCollection->has($type);

            $isProductSpecificAttribute = true;
            $id = null;
            $inputType = self::DEFAULT_INPUT_TYPE;
            $allowInput = false;
            $value = isset($productAttributeValues[$type]) ? $productAttributeValues[$type] : null;
            $shouldBeTextArea = mb_strlen($value) > 255;
            $isSuper = false;

            if ($isDefined) {
                $isProductSpecificAttribute = false;
                $attributeTransfer = $this->attributeTransferCollection->get($type);
                $id = $attributeTransfer->getIdProductManagementAttribute();
                $inputType = $attributeTransfer->getInputType();
                $allowInput = $attributeTransfer->getAllowInput();
                $isSuper = $attributeTransfer->getIsSuper();
            }

            if ($shouldBeTextArea) {
                $inputType = self::TEXT_AREA_INPUT_TYPE;
            }

            $checkboxDisabled = false;
            $valueDisabled = true;

            $values[$type] = [
                self::FORM_FIELD_ID => $id,
                self::FORM_FIELD_VALUE => $value,
                self::FORM_FIELD_NAME => isset($value),
                self::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                self::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                self::FORM_FIELD_SUPER => $isSuper,
                self::FORM_FIELD_INPUT_TYPE => $inputType,
                self::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                self::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                self::FORM_FIELD_ALLOW_INPUT => $allowInput,
            ];
        }

        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $isProductSpecificAttribute = false;
            $id = $attributeTransfer->getIdProductManagementAttribute();
            $allowInput = $attributeTransfer->getAllowInput();

            $value = isset($productAttributeValues[$type]) ? $productAttributeValues[$type] : null;

            $checkboxDisabled = false;
            $valueDisabled = true;

            $values[$type] = [
                self::FORM_FIELD_ID => $id,
                self::FORM_FIELD_VALUE => $value,
                self::FORM_FIELD_NAME => isset($value),
                self::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                self::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                self::FORM_FIELD_SUPER => $attributeTransfer->getIsSuper(),
                self::FORM_FIELD_INPUT_TYPE => $attributeTransfer->getInputType(),
                self::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                self::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                self::FORM_FIELD_ALLOW_INPUT => $allowInput,
            ];
        }

        return $values;
    }

    /**
     * @param array $productAttributeKeys
     * @param array $productAttributeValues
     *
     * @return array
     */
    protected function getProductAttributesFormOptions(array $productAttributeKeys, array $productAttributeValues)
    {
        $values = [];
        foreach ($productAttributeKeys as $key => $value) {
            $value = array_key_exists($key, $productAttributeValues) ? $productAttributeValues[$key] : null;

            $values[$key] = [
                self::FORM_FIELD_ID => null,
                self::FORM_FIELD_VALUE => $value,
                self::FORM_FIELD_NAME => isset($value),
                self::FORM_FIELD_PRODUCT_SPECIFIC => true,
                self::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($key),
                self::FORM_FIELD_SUPER => false,
                self::FORM_FIELD_INPUT_TYPE => 'text',
                self::FORM_FIELD_VALUE_DISABLED => true,
                self::FORM_FIELD_NAME_DISABLED => true,
                self::FORM_FIELD_ALLOW_INPUT => false,
            ];
        }

        return $values;
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function getProductAttributesFormValues(array $productAttributes)
    {
        $values = [];
        foreach ($productAttributes as $key => $value) {
            $id = null;
            $attributeTransfer = $this->attributeTransferCollection->get($key);
            if ($attributeTransfer) {
                $id = $attributeTransfer->getIdProductManagementAttribute();
            }

            if (!array_key_exists($key, $values)) {
                $values[$key] = [
                    AttributeAbstractForm::FIELD_NAME => false,
                    AttributeAbstractForm::FIELD_VALUE => $value,
                    AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID => $id,
                ];
            }
        }

        return $values;
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

        $transfer = $this->attributeTransferCollection->get($keyToLocalize);
        return $transfer->getKey();
    }

    /**
     * @param string $baseUrl
     *
     * @return string
     */
    protected function getImageUrl($baseUrl)
    {
        $url = $baseUrl;

        if (preg_match("#^\/(?!/).*$#", $url) === 1) {
            $url = $this->imageUrlPrefix . $url;
        }

        return $url;
    }
}
