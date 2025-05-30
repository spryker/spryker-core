<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use ArrayObject;
use Everon\Component\Collection\Collection;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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
use Spryker\Zed\ProductManagement\Communication\Reader\ProductAttributeReaderInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class AbstractProductFormDataProvider
{
    /**
     * @var string
     */
    public const LOCALE_NAME = 'locale_name';

    /**
     * @var string
     */
    public const FORM_FIELD_ID = 'id';

    /**
     * @var string
     */
    public const FORM_FIELD_VALUE = 'value';

    /**
     * @var string
     */
    public const FORM_FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FORM_FIELD_PRODUCT_SPECIFIC = 'product_specific';

    /**
     * @var string
     */
    public const FORM_FIELD_LABEL = 'label';

    /**
     * @var string
     */
    public const FORM_FIELD_SUPER = 'super';

    /**
     * @var string
     */
    public const FORM_FIELD_INPUT_TYPE = 'input_type';

    /**
     * @var string
     */
    public const FORM_FIELD_VALUE_DISABLED = 'value_disabled';

    /**
     * @var string
     */
    public const FORM_FIELD_NAME_DISABLED = 'name_disabled';

    /**
     * @var string
     */
    public const FORM_FIELD_ALLOW_INPUT = 'allow_input';

    /**
     * @var string
     */
    public const IMAGES = 'images';

    /**
     * @var string
     */
    public const DEFAULT_INPUT_TYPE = 'text';

    /**
     * @var string
     */
    public const TEXT_AREA_INPUT_TYPE = 'textarea';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     *
     * @var string
     */
    protected const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

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
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Everon\Component\Collection\CollectionInterface<\Generated\Shared\Transfer\ProductManagementAttributeTransfer>
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
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeInterface|null
     */
    protected $store;

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Reader\ProductAttributeReaderInterface|null
     */
    protected $productAttributeReader;

    /**
     * @var array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormDataProviderExpanderPluginInterface>
     */
    protected $productAbstractFormDataProviderExpanderPlugins = [];

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface $productImageFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Generated\Shared\Transfer\LocaleTransfer $currentLocale
     * @param array $taxCollection
     * @param string $imageUrlPrefix
     * @param \Spryker\Zed\ProductManagement\Communication\Reader\ProductAttributeReaderInterface|null $productAttributeReader
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormDataProviderExpanderPluginInterface> $productAbstractFormDataProviderExpanderPlugins
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductQueryContainerInterface $productQueryContainer,
        StockQueryContainerInterface $stockQueryContainer,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToProductImageInterface $productImageFacade,
        ProductManagementToPriceProductInterface $priceProductFacade,
        LocaleProvider $localeProvider,
        LocaleTransfer $currentLocale,
        array $taxCollection,
        $imageUrlPrefix,
        ?ProductAttributeReaderInterface $productAttributeReader = null,
        array $productAbstractFormDataProviderExpanderPlugins = []
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->productQueryContainer = $productQueryContainer;
        $this->stockQueryContainer = $stockQueryContainer;
        $this->productImageFacade = $productImageFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->localeProvider = $localeProvider;
        $this->productFacade = $productFacade;
        $this->currentLocale = $currentLocale;
        $this->taxCollection = $taxCollection;
        $this->imageUrlPrefix = $imageUrlPrefix;
        $this->productAttributeReader = $productAttributeReader;
        $this->productAbstractFormDataProviderExpanderPlugins = $productAbstractFormDataProviderExpanderPlugins;
        $this->attributeTransferCollection = $this->getAttributeTransferCollection();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstractTransfer
     *
     * @return mixed
     */
    public function getOptions(?ProductAbstractTransfer $productAbstractTransfer = null)
    {
        $localeCollection = $this->localeProvider->getLocaleCollection();

        $localizedAttributeOptions = [];
        foreach ($localeCollection as $localeTransfer) {
            $localizedAttributeOptions[$localeTransfer->getLocaleName()] = $this->convertAbstractLocalizedAttributesToFormOptions($productAbstractTransfer, $localeTransfer);
        }
        $localizedAttributeOptions[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE] = $this->convertAbstractLocalizedAttributesToFormOptions($productAbstractTransfer, null);

        $formOptions = [];
        $formOptions[ProductFormAdd::OPTION_ATTRIBUTE_SUPER] = $this->convertVariantAttributesToFormOptions($productAbstractTransfer);
        $formOptions[ProductFormAdd::OPTION_ATTRIBUTE_ABSTRACT] = $localizedAttributeOptions;

        $formOptions[ProductFormAdd::OPTION_ID_LOCALE] = $this->currentLocale->getIdLocale();
        $formOptions[ProductFormAdd::OPTION_LOCALE] = $this->currentLocale->getLocaleNameOrFail();
        $formOptions[ProductFormAdd::OPTION_TAX_RATES] = $this->taxCollection;

        return $formOptions;
    }

    /**
     * @param array|null $priceDimension
     *
     * @return array
     */
    protected function getDefaultFormFields(?array $priceDimension = null)
    {
        $data = [
            ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT => null,
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::FORM_ATTRIBUTE_SUPER => $this->getAttributeVariantDefaultFields(),
            ProductFormAdd::FORM_PRICE_DIMENSION => $priceDimension,
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
     * @param array<\Generated\Shared\Transfer\ProductImageSetTransfer> $imageSetTransferCollection
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
     * @param array<string, mixed> $data
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
     * @param bool $isNew
     *
     * @return array
     */
    protected function convertAbstractLocalizedAttributesToFormValues($isNew = false)
    {
        /** @var array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer> $values */
        $values = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $attributeValue = $values[$type] ?? null;

            if ($isNew) {
                $attributeValue = null;
            }

            $values[$type] = [
                AttributeAbstractForm::FIELD_NAME => $attributeValue !== null,
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
    protected function convertAbstractLocalizedAttributesToFormOptions(
        ?ProductAbstractTransfer $productAbstractTransfer = null,
        ?LocaleTransfer $localeTransfer = null
    ) {
        $values = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $isProductSpecificAttribute = false;
            $id = $attributeTransfer->getIdProductManagementAttribute();
            $isSuper = $attributeTransfer->getIsSuper();
            $inputType = $attributeTransfer->getInputType();
            $allowInput = $attributeTransfer->getAllowInput();
            $value = null;
            $checkboxDisabled = false;
            $valueDisabled = true;

            $values[$type] = [
                static::FORM_FIELD_ID => $id,
                static::FORM_FIELD_VALUE => $value,
                static::FORM_FIELD_NAME => $value !== null,
                static::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                static::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                static::FORM_FIELD_SUPER => $isSuper,
                static::FORM_FIELD_INPUT_TYPE => $inputType,
                static::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                static::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                static::FORM_FIELD_ALLOW_INPUT => $allowInput,
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
            $inputType = static::DEFAULT_INPUT_TYPE;
            $allowInput = false;
            $value = $productAttributeValues[$type] ?? null;
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $shouldBeTextArea = $value && mb_strlen($value) > 255;
            $checkboxDisabled = true;
            $valueDisabled = true;

            if ($shouldBeTextArea) {
                $inputType = static::TEXT_AREA_INPUT_TYPE;
            }

            $values[$type] = [
                static::FORM_FIELD_ID => $id,
                static::FORM_FIELD_VALUE => $value,
                static::FORM_FIELD_NAME => $value !== null,
                static::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                static::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                static::FORM_FIELD_SUPER => $isSuper,
                static::FORM_FIELD_INPUT_TYPE => $inputType,
                static::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                static::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                static::FORM_FIELD_ALLOW_INPUT => $allowInput,
            ];
        }

        return $values;
    }

    /**
     * @param bool $isNew
     *
     * @return array
     */
    protected function convertVariantAttributesToFormValues($isNew = false)
    {
        /** @var array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productAttributes */
        $productAttributes = [];

        $result = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            if (!$attributeTransfer->getIsSuper()) {
                continue;
            }

            $value = $productAttributes[$type] ?? null;

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

        $result = $result + $productValues;

        return $result;
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
                    $this->productFacade->getCombinedAbstractAttributeKeys($productAbstractTransfer, $localeTransfer),
                ));
            }
        }

        $values = [];
        foreach ($productAttributeKeys as $type) {
            $isDefined = $this->attributeTransferCollection->has($type);

            $isProductSpecificAttribute = true;
            $id = null;
            $inputType = static::DEFAULT_INPUT_TYPE;
            $allowInput = false;
            $value = '';
            $isSuper = false;

            if ($isDefined) {
                $isProductSpecificAttribute = false;
                /** @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer */
                $attributeTransfer = $this->attributeTransferCollection->get($type);
                $id = $attributeTransfer->getIdProductManagementAttribute();
                $inputType = $attributeTransfer->getInputType();
                $allowInput = $attributeTransfer->getAllowInput();
                $isSuper = $attributeTransfer->getIsSuper();
            }

            $inputType = static::TEXT_AREA_INPUT_TYPE;

            $checkboxDisabled = false;
            $valueDisabled = true;

            $values[$type] = [
                static::FORM_FIELD_ID => $id,
                static::FORM_FIELD_VALUE => $value,
                static::FORM_FIELD_NAME => (bool)$value,
                static::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                static::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                static::FORM_FIELD_SUPER => $isSuper,
                static::FORM_FIELD_INPUT_TYPE => $inputType,
                static::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                static::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                static::FORM_FIELD_ALLOW_INPUT => $allowInput,
            ];
        }

        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $isProductSpecificAttribute = false;
            $id = $attributeTransfer->getIdProductManagementAttribute();
            $allowInput = $attributeTransfer->getAllowInput();

            $value = null;

            $checkboxDisabled = false;
            $valueDisabled = true;

            $values[$type] = [
                static::FORM_FIELD_ID => $id,
                static::FORM_FIELD_VALUE => $value,
                static::FORM_FIELD_NAME => $value !== null,
                static::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                static::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                static::FORM_FIELD_SUPER => $attributeTransfer->getIsSuper(),
                static::FORM_FIELD_INPUT_TYPE => $attributeTransfer->getInputType(),
                static::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                static::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                static::FORM_FIELD_ALLOW_INPUT => $allowInput,
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
                static::FORM_FIELD_ID => null,
                static::FORM_FIELD_VALUE => $value,
                static::FORM_FIELD_NAME => $value !== null,
                static::FORM_FIELD_PRODUCT_SPECIFIC => true,
                static::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($key),
                static::FORM_FIELD_SUPER => false,
                static::FORM_FIELD_INPUT_TYPE => 'text',
                static::FORM_FIELD_VALUE_DISABLED => true,
                static::FORM_FIELD_NAME_DISABLED => true,
                static::FORM_FIELD_ALLOW_INPUT => false,
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
            /** @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null $attributeTransfer */
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

        /** @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null $attributeTransfer */
        $attributeTransfer = $this->attributeTransferCollection->get($keyToLocalize);

        return $attributeTransfer->getKey();
    }

    /**
     * @param string $baseUrl
     *
     * @return string
     */
    protected function getImageUrl($baseUrl)
    {
        $url = $baseUrl;

        if (preg_match("#^/(?!/)[\w/-]*\.[A-Za-z]{3,4}$#", $url) === 1) {
            $url = $this->imageUrlPrefix . $url;
        }

        return $url;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getProductAbstractPricesByPriceDimension(ProductAbstractTransfer $productAbstractTransfer, array $formData): ArrayObject
    {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setPriceDimension((new PriceProductDimensionTransfer())
                ->setType(static::PRICE_DIMENSION_DEFAULT));

        if ($formData[ProductFormAdd::FORM_PRICE_DIMENSION]) {
            $priceProductCriteriaTransfer->getPriceDimension()
                ->fromArray($formData[ProductFormAdd::FORM_PRICE_DIMENSION], true);
        }

        $priceProducts = $this->priceProductFacade->findProductAbstractPricesWithoutPriceExtraction(
            $productAbstractTransfer->getIdProductAbstract(),
            $priceProductCriteriaTransfer,
        );

        return new ArrayObject($priceProducts);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getProductConcretePricesByPriceDimension(
        ProductConcreteTransfer $productTransfer,
        ProductAbstractTransfer $productAbstractTransfer,
        array $formData
    ): ArrayObject {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setPriceDimension((new PriceProductDimensionTransfer())
                ->setType(static::PRICE_DIMENSION_DEFAULT));

        if ($formData[ProductFormAdd::FORM_PRICE_DIMENSION]) {
            $priceProductCriteriaTransfer->getPriceDimension()
                ->fromArray($formData[ProductFormAdd::FORM_PRICE_DIMENSION], true);
        }

        $priceProducts = $this->priceProductFacade->findProductConcretePricesWithoutPriceExtraction(
            $productTransfer->getIdProductConcrete(),
            $productAbstractTransfer->getIdProductAbstract(),
            $priceProductCriteriaTransfer,
        );

        return new ArrayObject($priceProducts);
    }

    /**
     * @return \Everon\Component\Collection\Collection
     */
    protected function getAttributeTransferCollection(): Collection
    {
        if ($this->productAttributeReader === null) {
            return new Collection([]);
        }

        return new Collection($this->productAttributeReader->getProductSuperAttributesIndexedByAttributeKey());
    }
}
