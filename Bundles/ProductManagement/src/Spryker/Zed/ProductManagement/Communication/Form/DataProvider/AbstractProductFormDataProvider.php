<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Shared\Library\Collection\Collection;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessor;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\PriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
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
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface
     */
    protected $productManagementFacade;

    /**
     * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]|\Spryker\Shared\Library\Collection\CollectionInterface
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


    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductQueryContainerInterface $productQueryContainer,
        StockQueryContainerInterface $stockQueryContainer,
        ProductManagementToPriceInterface $priceFacade,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToProductImageInterface $productImageFacade,
        ProductManagementFacadeInterface $productManagementFacade,
        LocaleProvider $localeProvider,
        LocaleTransfer $currentLocale,
        array $attributeCollection,
        array $taxCollection,
        $imageUrlPrefix
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->productQueryContainer = $productQueryContainer;
        $this->stockQueryContainer = $stockQueryContainer;
        $this->productImageFacade = $productImageFacade;
        $this->localeProvider = $localeProvider;
        $this->priceFacade = $priceFacade;
        $this->productFacade = $productFacade;
        $this->productManagementFacade = $productManagementFacade;
        $this->currentLocale = $currentLocale;
        $this->attributeTransferCollection = new Collection($attributeCollection);
        $this->taxCollection = $taxCollection;
        $this->imageUrlPrefix = $imageUrlPrefix;
    }

    /**
     * @param int|null $idProductAbstract |null
     *
     * @return mixed
     */
    public function getOptions($idProductAbstract = null)
    {
        $isNew = $idProductAbstract === null;
        $attributeProcessor = $this->productManagementFacade->getProductAttributesByAbstractProductId($idProductAbstract);

        $localeCollection = $this->localeProvider->getLocaleCollection();

        $localizedAttributeOptions = [];
        foreach ($localeCollection as $localeCode) {
            $localizedAttributeOptions[$localeCode] = $this->convertAbstractLocalizedAttributesToFormOptions($attributeProcessor, $localeCode, $isNew);
        }
        $localizedAttributeOptions[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE] = $this->convertAbstractLocalizedAttributesToFormOptions($attributeProcessor, null, $isNew);

        $formOptions[ProductFormAdd::OPTION_ATTRIBUTE_ABSTRACT] = $localizedAttributeOptions;
        $formOptions[ProductFormAdd::OPTION_ATTRIBUTE_VARIANT] = $this->convertVariantAttributesToFormOptions($attributeProcessor, $isNew);

        $formOptions[ProductFormAdd::OPTION_ID_LOCALE] = $this->currentLocale->getIdLocale();
        $formOptions[ProductFormAdd::OPTION_TAX_RATES] = $this->taxCollection;

        return $formOptions;
    }

    /**
     * @param int|null $idProductAbstract
     *
     * @return array
     */
    protected function getDefaultFormFields($idProductAbstract = null)
    {
        $data = [
            ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT => null,
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::FORM_ATTRIBUTE_VARIANT => $this->getAttributeVariantDefaultFields(),
            ProductFormAdd::FORM_PRICE_AND_TAX => [
                PriceForm::FIELD_PRICE => 0,
                PriceForm::FIELD_TAX_RATE => 0,
            ]
        ];

        $data = array_merge($data, $this->getGeneralAttributesDefaultFields());
        $data = array_merge($data, $this->getSeoDefaultFields());
        $data = array_merge($data, $this->getAttributeAbstractDefaultFields());
        $data = array_merge($data, $this->getImagesDefaultFields());

        return $data;
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
        $localeCollection = $this->localeProvider->getLocaleCollection();

        $result = [];
        $defaults = [];
        foreach ($localeCollection as $localeCode) {
            $localeTransfer = $this->localeProvider->getLocaleTransfer($localeCode);

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

            $formName = ProductFormAdd::getImagesFormName($localeCode);
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
            $image[ImageCollectionForm::FIELD_IMAGE_PREVIEW] = $this->imageUrlPrefix . $image[ImageCollectionForm::FIELD_IMAGE_SMALL];
            $image[ImageCollectionForm::FIELD_IMAGE_PREVIEW_LARGE_URL] = $this->imageUrlPrefix . $image[ImageCollectionForm::FIELD_IMAGE_LARGE];
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
        foreach ($availableLocales as $id => $localeCode) {
            $key = ProductFormAdd::getGeneralFormName($localeCode);
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
        foreach ($availableLocales as $id => $localeCode) {
            $key = ProductFormAdd::getSeoFormName($localeCode);
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
        $attributeProcessor = new AttributeProcessor();
        return $this->convertVariantAttributesToFormValues($attributeProcessor, true);
    }

    /**
     * @return array
     */
    protected function getAttributeAbstractDefaultFields()
    {
        $availableLocales = $this->localeProvider->getLocaleCollection();
        $attributeProcessor = $this->productManagementFacade->getProductAttributesByAbstractProductId(null);

        $result = [];
        foreach ($availableLocales as $id => $localeCode) {
            $key = ProductFormAdd::getAbstractAttributeFormName($localeCode);
            $data = $this->convertAbstractLocalizedAttributesToFormValues($attributeProcessor, $localeCode, true);
            $result[$key] = $data;
        }

        $defaultKey = ProductFormAdd::getLocalizedPrefixName(ProductFormAdd::FORM_ATTRIBUTE_ABSTRACT, ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $result[$defaultKey] = $this->convertAbstractLocalizedAttributesToFormValues($attributeProcessor, null, true);

        return $result;
    }

    /**
     * @return array
     */
    protected function getImagesDefaultFields()
    {
        $availableLocales = $this->localeProvider->getLocaleCollection();
        $data = [
            ImageSetForm::FIELD_SET_ID => null,
            ImageSetForm::FIELD_SET_NAME => null,
            ImageSetForm::PRODUCT_IMAGES => [[
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
            ]]
        ];

        $result = [];
        foreach ($availableLocales as $id => $localeCode) {
            $key = ProductFormAdd::getImagesFormName($localeCode);
            $result[$key] = [$data];
        }

        $defaultKey = ProductFormAdd::getLocalizedPrefixName(ProductFormAdd::FORM_IMAGE_SET, ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        $result[$defaultKey] = [$data];

        return $result;
    }

    /**
     * @return array
     */
    public static function getImageFields()
    {
        return [
            ImageSetForm::FIELD_SET_ID => null,
            ImageSetForm::FIELD_SET_NAME => null,
            ImageSetForm::PRODUCT_IMAGES => [[
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
            ]]
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
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface $attributeProcessor
     * @param string $localeCode
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertAbstractLocalizedAttributesToFormValues(AttributeProcessorInterface $attributeProcessor, $localeCode = null, $isNew = false)
    {
        if ($localeCode === null) {
            $attributes = $attributeProcessor->mergeAttributes($localeCode);
        } else {
            $attributes = $attributeProcessor->getAbstractLocalizedAttributesByLocaleCode($localeCode);
        }

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

        $productValues = $this->getProductAttributesFormValues($attributes);

        return array_merge($productValues, $values);
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface $attributeProcessor
     * @param string $localeCode
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertAbstractLocalizedAttributesToFormOptions(AttributeProcessorInterface $attributeProcessor, $localeCode = null, $isNew = false)
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
                self::FORM_FIELD_ALLOW_INPUT => $allowInput
            ];
        }

        $productAttributeKeys = $attributeProcessor->getAllKeys();
        if ($localeCode === null) { //default tab
            $productAttributeValues = $attributeProcessor->getAbstractAttributes();
        } else {
            $productAttributeValues = $attributeProcessor->getAbstractLocalizedAttributesByLocaleCode($localeCode);
        }

        foreach ($productAttributeKeys as $type => $tmp) {
            $isDefined = $this->attributeTransferCollection->has($type);

            $isProductSpecificAttribute = true;
            $id = null;
            $isSuper = false;
            $inputType = self::DEFAULT_INPUT_TYPE;
            $allowInput = false;
            $value = isset($productAttributeValues[$type]) ? $productAttributeValues[$type] : null;
            $shouldBeTextArea = mb_strlen($value) > 255;
            $checkboxDisabled = true;
            $valueDisabled = true;

            if ($isDefined) {
                continue;
            }

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
                self::FORM_FIELD_ALLOW_INPUT => $allowInput
            ];
        }

        return $values;
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface $attributeProcessor
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertVariantAttributesToFormValues(AttributeProcessorInterface $attributeProcessor, $isNew = false)
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
                AttributeAbstractForm::FIELD_VALUE_HIDDEN_ID => $attributeTransfer->getIdProductManagementAttribute()
            ];
        }

        $productValues = $this->getProductAttributesFormValues($productAttributes);

        return array_merge($productValues, $result);
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface $attributeProcessor
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertVariantAttributesToFormOptions(AttributeProcessorInterface $attributeProcessor, $isNew = false)
    {
        $productAttributeKeys = $attributeProcessor->getAllKeys();
        $productAttributeValues = [];

        $values = [];
        foreach ($productAttributeKeys as $type => $tmp) {
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
                self::FORM_FIELD_ALLOW_INPUT => $allowInput
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
                self::FORM_FIELD_ALLOW_INPUT => $allowInput
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
                self::FORM_FIELD_ALLOW_INPUT => false
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

    protected function getLocalizedAttributeMetadataKey($keyToLocalize)
    {
        if (!$this->attributeTransferCollection->has($keyToLocalize)) {
            return $keyToLocalize;
        }

        $transfer = $this->attributeTransferCollection->get($keyToLocalize);
        //TODO implement translations
        return $transfer->getKey();
    }

    /**
     * @param string $inputType
     *
     * @return string
     */
    protected function getHtmlInputTypeByInput($inputType)
    {
        switch ($inputType) {
            case 'textarea':
                return 'textarea';
                break;

            default:
                return 'text';
                break;
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function slugify($value)
    {
        if (function_exists('iconv')) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = preg_replace("/[^a-zA-Z0-9 -]/", "", trim($value));
        $value = mb_strtolower($value);
        $value = str_replace(' ', '-', $value);

        return $value;
    }

}
