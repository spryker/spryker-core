<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessor;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormPrice;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormSeo;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class AbstractProductFormDataProvider
{

    const LOCALE_NAME = 'locale_name';
    const DEFAULT_LOCALE = 'default_locale';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface
     */
    protected $productManagementFacade;

    /**
     * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected $attributeTransferCollection = [];

    /**
     * @var array
     */
    protected $taxCollection = [];


    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementToPriceInterface $priceFacade,
        ProductFacadeInterface $productFacade,
        ProductManagementFacadeInterface $productManagementFacade,
        ProductManagementToLocaleInterface $localeFacade,
        array $attributeCollection,
        array $taxCollection
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productQueryContainer = $productQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->priceFacade = $priceFacade;
        $this->productFacade = $productFacade;
        $this->productManagementFacade = $productManagementFacade;
        $this->locale = $localeFacade->getCurrentLocale();
        $this->attributeTransferCollection = $attributeCollection;
        $this->taxCollection = $taxCollection;
    }

    /**
     * @param int|null $idProductAbstract |null
     *
     * @return mixed
     */
    public function getOptions($idProductAbstract = null)
    {
        $attributes = $this->getAttributesForAbstractProduct($idProductAbstract);

        $formOptions[ProductFormAdd::ATTRIBUTE_ABSTRACT] = $this->convertAbstractAttributesToFormValues($attributes, true);
        //$formOptions[ProductFormAdd::ATTRIBUTE_VARIANT] = $formOptions[ProductFormAdd::ATTRIBUTE_ABSTRACT];

        $formOptions[ProductFormAdd::TAX_SET] = $this->taxCollection;
        $formOptions[ProductFormAdd::ID_LOCALE] = $this->localeFacade->getCurrentLocale()->getIdLocale();

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
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::ATTRIBUTE_VARIANT => $this->getAttributeVariantDefaultFields(),
            ProductFormAdd::TAX_SET => $this->getPriceAndStockDefaultFields(),
            ProductFormAdd::PRICE_AND_STOCK => [
                ProductFormPrice::FIELD_PRICE => 0,
                ProductFormPrice::FIELD_TAX_RATE => 0,
                ProductFormPrice::FIELD_STOCK => 0
            ]
        ];

        $data = array_merge($data, $this->getGeneralAttributesDefaultFields());
        $data = array_merge($data, $this->getSeoDefaultFields());
        $data = array_merge($data, $this->getAttributeAbstractDefaultFields());

        return $data;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    public function getAttributesForAbstractProduct($idProductAbstract = null)
    {
        return $this->productManagementFacade
            ->getProductAttributesByAbstractProductId($idProductAbstract);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer[] $attributes
     *
     * @return array
     */
    public function getLocalizedAttributesAsArray($attributes)
    {
        $localizedAttributes = [];
        foreach ($attributes as $attribute) {
            $localizedAttributes[$attribute->getLocale()->getLocaleName()] = $attribute->toArray();
        }

        return $localizedAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer[] $attributes
     *
     * @return array
     */
    public function getAbstractLocalizedAttributesAsArray($attributes)
    {
        $localizedAttributes = [];
        foreach ($attributes as $attribute) {
            $localizedAttributes[$attribute->getLocale()->getLocaleName()] = $attribute->toArray();
        }

        return $localizedAttributes;
    }

    /**
     * @return array
     */
    public function getGeneralAttributesDefaultFields()
    {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        $result = [];
        foreach ($availableLocales as $id => $localeCode) {
            $key = ProductFormAdd::getGeneralFormName($localeCode);
            $result[$key] = [
                ProductFormAdd::FIELD_NAME => null,
                ProductFormAdd::FIELD_DESCRIPTION => null,
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getSeoDefaultFields()
    {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        $result = [];
        foreach ($availableLocales as $id => $localeCode) {
            $key = ProductFormAdd::getSeoFormName($localeCode);
            $result[$key] = [
                ProductFormSeo::FIELD_META_TITLE => null,
                ProductFormSeo::FIELD_META_KEYWORDS => null,
                ProductFormSeo::FIELD_META_DESCRIPTION => null,
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAttributeVariantDefaultFields()
    {
        $attributeProcessor = new AttributeProcessor();
        return $this->convertAbstractAttributesToFormValues($attributeProcessor, true);
    }

    /**
     * @return array
     */
    public function getAttributeAbstractDefaultFields()
    {
        $availableLocales = $this->localeFacade->getAvailableLocales();
        $attributeProcessor = $this->getAttributesForAbstractProduct(null);
        $data = $this->convertAbstractAttributesToFormValues($attributeProcessor, true);

        $result = [];
        foreach ($availableLocales as $id => $localeCode) {
            $key = ProductFormAdd::getAbstractAttributeFormName($localeCode);
            $result[$key] = $data;
        }

        $defaultKey = ProductFormAdd::getLocalizedPrefixName(ProductFormAdd::ATTRIBUTE_ABSTRACT, self::DEFAULT_LOCALE);
        $result[$defaultKey] = $data;

        return $result;
    }

    /**
     * @return array
     */
    public function getPriceAndStockDefaultFields()
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
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertAbstractAttributesToFormValues(AttributeProcessorInterface $attributeProcessor, $isNew = false)
    {
        $productAttributes = $attributeProcessor->getAbstractAttributes()->toArray(true);

        $values = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $isProductSpecificAttribute = (!$isNew) && !array_key_exists($type, $productAttributes);
            $value = isset($productAttributes[$type]) ? $productAttributes[$type] : null;

            $isLocalized = $this->attributeTransferCollection[$type]->getIsLocalized();
            $isMulti = $this->attributeTransferCollection[$type]->getIsMultiple();

            $valueDisabled = !$isProductSpecificAttribute;
            if ($isNew) {
                $valueDisabled = true;
            }

            $checkboxDisabled = $isProductSpecificAttribute;
            if ($isNew) {
                $checkboxDisabled = false;
            }

            $values[$type] = [
                'value' => $value,
                'name' => isset($value),
                'product_specific' => $isProductSpecificAttribute,
                'label' => $this->getLocalizedAttributeMetadataKey($type),
                'multiple' => $isMulti,
                'localized' => $isLocalized,
                'input' => $this->getHtmlInputTypeByInput($attributeTransfer->getInputType()),
                'value_disabled' => $valueDisabled,
                'name_disabled' => $checkboxDisabled
            ];

            //append product custom attributes
            foreach ($productAttributes as $key => $value) {
                $isMulti = isset($value) && is_array($value);
                if (!array_key_exists($key, $values)) {
                    $values[$key] = [
                        'value' => $value,
                        'name' => false,
                        'product_specific' => true,
                        'label' => $this->getLocalizedAttributeMetadataKey($key),
                        'multiple' => $isMulti,
                        'localized' => false,
                        'input' => 'text',
                        'value_disabled' => true,
                        'name_disabled' => true
                    ];
                }
            }
        }

        return $values;
    }

/*
    protected function convertSelectedAttributeMetadataToFormValues(AttributeProcessorInterface $attributeProcessor)
    {
        $productAttributes = $attributeProcessor->getAttributes()->toArray(true);
        $values = [];
        foreach ($this->attributeTransferCollection as $type => $transfer) {
            $isCustom = !array_key_exists($type, $this->attributeTransferCollection);
            $isProductSpecificAttribute = !array_key_exists($type, $productAttributes);
            $isMulti = isset($productAttributes[$type]) && is_array($productAttributes[$type]);
            $value = isset($productAttributes[$type]) ? true : false;
            $isLocalized = false;

            $isLocalized = $this->attributeTransferCollection[$type]->getIsLocalized();

            if ($isLocalized || $isMulti) {
                continue;
            }

            if ($isMulti && $value) {
                $value = !empty($value);
            }

            $values[$type] = [
                'value' => null,
                'name' => $isCustom,
                'product_specific' => $isProductSpecificAttribute,
                'custom' => $isCustom,
                'label' => $this->getLocalizedAttributeMetadataKey($type),
                'multiple' => $isMulti,
                'localized' => $isLocalized,
                'input_type' => $transfer->getInputType(),
                'allow_input' => $transfer->getAllowInput(),
            ];
        }

        //append product custom attributes
        foreach ($productAttributes as $key => $value) {
            $isMulti = isset($value) && is_array($value);
            if (!array_key_exists($key, $values)) {
                $values[$key] = [
                    'value' => null,
                    'name' => true,
                    'product_specific' => true,
                    'custom' => true,
                    'label' => $this->getLocalizedAttributeMetadataKey($key),
                    'multiple' => $isMulti,
                    'localized' => false
                ];
            }
        }

        return $values;
    }*/

    protected function getLocalizedAttributeMetadataKey($keyToLocalize)
    {
        return $keyToLocalize;
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

}
