<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessor;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\PriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class AbstractProductFormDataProvider
{

    const LOCALE_NAME = 'locale_name';

    const FORM_FIELD_ID = 'id';
    const FORM_FIELD_VALUE = 'value';
    const FORM_FIELD_NAME = 'name';
    const FORM_FIELD_PRODUCT_SPECIFIC = 'product_specific';
    const FORM_FIELD_LABEL = 'label';
    const FORM_FIELD_MULTIPLE = 'multiple';
    const FORM_FIELD_INPUT_TYPE = 'input_type';
    const FORM_FIELD_VALUE_DISABLED = 'value_disabled';
    const FORM_FIELD_NAME_DISABLED = 'name_disabled';
    const FORM_FIELD_ALLOW_INPUT = 'allow_input';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

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
        ProductManagementToProductInterface $productFacade,
        ProductManagementFacadeInterface $productManagementFacade,
        LocaleProvider $localeProvider,
        LocaleTransfer $currentLocale,
        array $attributeCollection,
        array $taxCollection
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productQueryContainer = $productQueryContainer;
        $this->localeProvider = $localeProvider;
        $this->priceFacade = $priceFacade;
        $this->productFacade = $productFacade;
        $this->productManagementFacade = $productManagementFacade;
        $this->currentLocale = $currentLocale;
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
        $isNew = $idProductAbstract === null;
        $attributes = $this->getAttributesForAbstractProduct($idProductAbstract);

        $formOptions[ProductFormAdd::ATTRIBUTE_ABSTRACT] = $this->convertAbstractLocalizedAttributesToFormOptions($attributes, $isNew);
        $formOptions[ProductFormAdd::ATTRIBUTE_VARIANT] = $this->convertVariantAttributesToFormOptions($attributes, $isNew);

        $formOptions[ProductFormAdd::ID_LOCALE] = $this->currentLocale->getIdLocale();
        $formOptions[ProductFormAdd::OPTION_TAX_RATES] = $this->taxCollection;

        s($formOptions);
        ob_flush();

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
            ProductFormAdd::PRICE_AND_TAX => [
                PriceForm::FIELD_PRICE => 0,
                PriceForm::FIELD_TAX_RATE => 0,
            ],
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
     * @return array
     */
    public function getGeneralAttributesDefaultFields()
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
    public function getSeoDefaultFields()
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
    public function getAttributeVariantDefaultFields()
    {
        $attributeProcessor = new AttributeProcessor();
        return $this->convertVariantAttributesToFormValues($attributeProcessor, true);
    }

    /**
     * @return array
     */
    public function getAttributeAbstractDefaultFields()
    {
        $availableLocales = $this->localeProvider->getLocaleCollection();
        $attributeProcessor = $this->getAttributesForAbstractProduct(null);
        $data = $this->convertAbstractLocalizedAttributesToFormValues($attributeProcessor, true);

        $result = [];
        foreach ($availableLocales as $id => $localeCode) {
            $key = ProductFormAdd::getAbstractAttributeFormName($localeCode);
            $result[$key] = $data;
        }

        $defaultKey = ProductFormAdd::getLocalizedPrefixName(ProductFormAdd::ATTRIBUTE_ABSTRACT, ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
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
    protected function convertAbstractLocalizedAttributesToFormValues(AttributeProcessorInterface $attributeProcessor, $isNew = false)
    {
        $productAttributes = $attributeProcessor->getAbstractAttributes()->toArray(true);

        $values = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $value = isset($productAttributes[$type]) ? $productAttributes[$type] : null;
            $isMultiple = $this->attributeTransferCollection[$type]->getIsMultiple();

            if ($isNew) {
                $value = null;
            }

            if ($isMultiple && !is_array($value)) {
                $value = [$value];
            }

            $values[$type] = [
                self::FORM_FIELD_NAME => null,
                self::FORM_FIELD_VALUE => $value,
            ];
        }

        $productValues = $this->getProductAttributesFormValues($productAttributes);

        return array_merge($productValues, $values);
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface $attributeProcessor
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertAbstractLocalizedAttributesToFormOptions(AttributeProcessorInterface $attributeProcessor, $isNew = false)
    {
        $productAttributes = $attributeProcessor->getAbstractAttributes()->toArray(true);

        $values = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $isProductSpecificAttribute =  !$isNew && !array_key_exists($type, $productAttributes);
            $value = isset($productAttributes[$type]) ? $productAttributes[$type] : 'foo';
            $isMultiple = $this->attributeTransferCollection[$type]->getIsMultiple();
            $checkboxDisabled = $attributeTransfer->getAllowInput() === false;
            $valueDisabled = true;

            if ($isProductSpecificAttribute) {
                $checkboxDisabled = true;
            }

            $values[$type] = [
                self::FORM_FIELD_ID => $attributeTransfer->getIdProductManagementAttribute(),
                self::FORM_FIELD_NAME => isset($value),
                self::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                self::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                self::FORM_FIELD_MULTIPLE => $isMultiple,
                self::FORM_FIELD_INPUT_TYPE => $this->getHtmlInputTypeByInput($attributeTransfer->getInputType()),
                self::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                self::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                self::FORM_FIELD_ALLOW_INPUT => $attributeTransfer->getAllowInput()
            ];
        }

        $productValues = $this->getProductAttributesFormOptions($productAttributes);

        return array_merge($productValues, $values);
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface $attributeProcessor
     * @param bool|false $isNew
     *
     * @return array
     */
    protected function convertVariantAttributesToFormValues(AttributeProcessorInterface $attributeProcessor, $isNew = false)
    {
        $productAttributes = $attributeProcessor->getAbstractAttributes()->toArray(true);

        $result = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $value = isset($productAttributes[$type]) ? $productAttributes[$type] : null;
            $isMultiple = $this->attributeTransferCollection[$type]->getIsMultiple();

            $value = 'shit';
            if ($isMultiple && !is_array($value)) {
                $value = [$value];
            }

            $result[$type] = [
                self::FORM_FIELD_NAME => null,
                self::FORM_FIELD_VALUE => []
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
        $productAttributes = $attributeProcessor->getAbstractAttributes()->toArray(true);

        $values = [];
        foreach ($this->attributeTransferCollection as $type => $attributeTransfer) {
            $isProductSpecificAttribute = !array_key_exists($type, $productAttributes);
            $value = isset($productAttributes[$type]) ? $productAttributes[$type] : null;
            $isMulti = $this->attributeTransferCollection[$type]->getIsMultiple();
            $valueDisabled = !$isProductSpecificAttribute;

            if ($isNew) {
                $valueDisabled = false;
            }

            $checkboxDisabled = $isProductSpecificAttribute && $attributeTransfer->getAllowInput();
            if ($isNew) {
                $checkboxDisabled = false;
            }

            if (!$attributeTransfer->getAllowInput()) {
                $valueDisabled = true;
            }

            if ($checkboxDisabled) {
                $valueDisabled = true;
            }

            $values[$type] = [
                self::FORM_FIELD_ID => $attributeTransfer->getIdProductManagementAttribute(),
                self::FORM_FIELD_NAME => isset($value),
                self::FORM_FIELD_PRODUCT_SPECIFIC => $isProductSpecificAttribute,
                self::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($type),
                self::FORM_FIELD_MULTIPLE => $isMulti,
                self::FORM_FIELD_INPUT_TYPE => $this->getHtmlInputTypeByInput($attributeTransfer->getInputType()),
                self::FORM_FIELD_VALUE_DISABLED => $valueDisabled,
                self::FORM_FIELD_NAME_DISABLED => $checkboxDisabled,
                self::FORM_FIELD_ALLOW_INPUT => $attributeTransfer->getAllowInput()
            ];
        }

        $productValues = $this->getProductAttributesFormOptions($productAttributes);

        return array_merge($productValues, $values);
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function getProductAttributesFormOptions(array $productAttributes)
    {
        $values = [];
        foreach ($productAttributes as $key => $value) {
            $isMultiple = isset($value) && is_array($value);
            if (!array_key_exists($key, $values)) {
                $values[$key] = [
                    self::FORM_FIELD_ID => null,
                    self::FORM_FIELD_VALUE => $value,
                    self::FORM_FIELD_NAME => false,
                    self::FORM_FIELD_PRODUCT_SPECIFIC => true,
                    self::FORM_FIELD_LABEL => $this->getLocalizedAttributeMetadataKey($key),
                    self::FORM_FIELD_MULTIPLE => $isMultiple,
                    self::FORM_FIELD_INPUT_TYPE => 'text',
                    self::FORM_FIELD_VALUE_DISABLED => true,
                    self::FORM_FIELD_NAME_DISABLED => true,
                    self::FORM_FIELD_ALLOW_INPUT => false
                ];
            }
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
            $isMultiple = isset($value) && is_array($value);

            if ($isMultiple && !is_array($value)) {
                $value = [$value];
            }

            if (!array_key_exists($key, $values)) {
                $values[$key] = [
                    self::FORM_FIELD_NAME => false,
                    self::FORM_FIELD_VALUE => $value,
                ];
            }
        }

        return $values;
    }

    protected function getLocalizedAttributeMetadataKey($keyToLocalize)
    {
        if (!array_key_exists($keyToLocalize, $this->attributeTransferCollection)) {
            return $keyToLocalize;
        }

        $transfer = $this->attributeTransferCollection[$keyToLocalize];
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

}
