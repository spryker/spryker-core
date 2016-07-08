<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessor;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAttributeMetadata;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormPrice;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormSeo;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class AbstractProductFormDataProvider
{

    const LOCALE_NAME = 'locale_name';

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
     * @var \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[]
     */
    protected $attributeMetadataTransferCollection = [];

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
        array $attributeMetadataCollection,
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
        $this->attributeMetadataTransferCollection = $attributeMetadataCollection;
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

        $formOptions[ProductFormAdd::ATTRIBUTE_METADATA] = $this->convertSelectedAttributeValuesToFormValues($attributes);
        $formOptions[ProductFormAdd::ATTRIBUTE_VALUES] = $formOptions[ProductFormAdd::ATTRIBUTE_METADATA];

        $formOptions[ProductFormAdd::TAX_SET] = $this->taxCollection;
        $formOptions[ProductFormAdd::ID_LOCALE] = $this->localeFacade->getCurrentLocale()->getIdLocale();

        return $formOptions;
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        return [
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::GENERAL => $this->getLocalizedAttributesDefaultFields(),
            ProductFormAdd::ATTRIBUTE_METADATA => $this->getAttributeMetadataDefaultFields(),
            ProductFormAdd::ATTRIBUTE_VALUES => $this->getAttributeValuesDefaultFields(),
            ProductFormAdd::TAX_SET => $this->getPriceAndStockDefaultFields(),
            ProductFormAdd::SEO => $this->getSeoDefaultFields(),
            ProductFormAdd::PRICE_AND_STOCK => [
                ProductFormPrice::FIELD_PRICE => 0,
                ProductFormPrice::FIELD_TAX_RATE => 0,
                ProductFormPrice::FIELD_STOCK => 0
            ]
        ];
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array
     */
    public function getLocalizedAbstractAttributes(ProductAbstractTransfer $productAbstractTransfer)
    {
        $localizedAttributes = [];
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $attribute) {
            $localizedAttributes[$attribute->getLocale()->getLocaleName()] = $attribute->toArray();
        }

        return $localizedAttributes;
    }

    /**
     * @return array
     */
    public function getLocalizedAttributesDefaultFields()
    {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        $fields = [];
        foreach ($availableLocales as $id => $code) {
            $fields[$code] = [
                ProductFormAdd::FIELD_NAME => null,
                ProductFormAdd::FIELD_DESCRIPTION => null,
            ];
        }

        return $fields;
    }

    /**
     * @return array
     */
    public function getSeoDefaultFields()
    {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        $fields = [];
        foreach ($availableLocales as $id => $code) {
            $fields[$code] = [
                ProductFormSeo::FIELD_META_TITLE => null,
                ProductFormSeo::FIELD_META_KEYWORDS => null,
                ProductFormSeo::FIELD_META_DESCRIPTION => null,
            ];
        }

        return $fields;
    }

    /**
     * @return array
     */
    public function getAttributeValuesDefaultFields()
    {
        $attributeProcessor = new AttributeProcessor();
        return $this->convertSelectedAttributeValuesToFormValues($attributeProcessor);
    }

    /**
     * @return array
     */
    public function getAttributeMetadataDefaultFields()
    {
        $attributeProcessor = new AttributeProcessor();
        return $this->convertSelectedAttributeMetadataToFormValues($attributeProcessor);
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
     *
     * @return array
     */
    protected function convertSelectedAttributeValuesToFormValues(AttributeProcessorInterface $attributeProcessor)
    {
        $productAttributes = $attributeProcessor->getAttributes()->toArray(true);

        $values = [];
        foreach ($this->attributeMetadataTransferCollection as $type => $transfer) {
            $isCustom = !array_key_exists($type, $this->attributeTransferCollection);
            $isProductSpecificAttribute = !array_key_exists($type, $productAttributes);
            $isMulti = isset($productAttributes[$type]) && is_array($productAttributes[$type]);
            $value = isset($productAttributes[$type]) ? $productAttributes[$type] : null;
            $isLocalized = false;

            if (array_key_exists($type, $this->attributeTransferCollection)) {
                $isLocalized = $this->attributeTransferCollection[$type]->getIsLocalized();
                $isMulti = $this->attributeTransferCollection[$type]->getIsMultiple();
                //$value = $this->getValueBasedOnInputType($this->attributeTransferCollection[$type], $value);
            }

            if ($isLocalized || $isMulti) {
                continue;
            }

            $values[$type] = [
                'value' => $value,
                'name' => $isCustom,
                'product_specific' => $isProductSpecificAttribute,
                'custom' => $isCustom,
                'label' => $this->getLocalizedAttributeMetadataKey($type),
                'multiple' => $isMulti,
                'localized' => $isLocalized
            ];

            //append product custom attributes
            foreach ($productAttributes as $key => $value) {
                $isMulti = isset($value) && is_array($value);
                if (!array_key_exists($key, $values)) {
                    $values[$key] = [
                        'value' => $value,
                        'name' => $isCustom,
                        'product_specific' => true,
                        'custom' => true,
                        'label' => $this->getLocalizedAttributeMetadataKey($key),
                        'multiple' => $isMulti,
                        'localized' => false
                    ];
                }
            }
        }

        return $values;
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface $attributeProcessor
     *
     * @return array
     */
    protected function convertSelectedAttributeMetadataToFormValues(AttributeProcessorInterface $attributeProcessor)
    {
        $productAttributes = $attributeProcessor->getAttributes()->toArray(true);
        $values = [];
        foreach ($this->attributeMetadataTransferCollection as $type => $transfer) {
            $isCustom = !array_key_exists($type, $this->attributeTransferCollection);
            $isProductSpecificAttribute = !array_key_exists($type, $productAttributes);
            $isMulti = isset($productAttributes[$type]) && is_array($productAttributes[$type]);
            $value = isset($productAttributes[$type]) ? true : false;
            $isLocalized = false;

            if (array_key_exists($type, $this->attributeTransferCollection)) {
                $isLocalized = $this->attributeTransferCollection[$type]->getIsLocalized();
            }

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
                'localized' => $isLocalized
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
    }

    protected function getLocalizedAttributeMetadataKey($keyToLocalize)
    {
        if (!isset($this->attributeMetadataTransferCollection[$keyToLocalize])) {
            return $keyToLocalize;
        }

        if (!isset($this->attributeTransferCollection[$keyToLocalize])) {
            return $keyToLocalize;
        }

        $attributeTransfer = $this->attributeTransferCollection[$keyToLocalize];
        foreach ($attributeTransfer->getLocalizedAttributes() as $localizedAttribute) {
            if ((int)$localizedAttribute->getFkLocale() === (int)$this->localeFacade->getCurrentLocale()->getIdLocale()) {
                return $localizedAttribute->getName();
            }
        }

        return $keyToLocalize;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     * @param mixed $value
     *
     * @return mixed
     */
    protected function getValueBasedOnInputType(ProductManagementAttributeTransfer $attributeTransfer, $value)
    {
        switch ($attributeTransfer->getInput()->getInput()) {
            case 'text':
            default:
                if (is_array($value)) {
                    sd($value);
                }
                return $value;
                break;
        }
    }

}
