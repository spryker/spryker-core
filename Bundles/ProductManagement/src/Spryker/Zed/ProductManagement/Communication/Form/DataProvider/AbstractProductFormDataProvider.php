<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
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
    protected $attributeMetadataCollection = [];

    /**
     * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected $attributeCollection = [];

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
        $this->attributeMetadataCollection = $this->reindexAttributeMetadataCollection($attributeMetadataCollection);
        $this->attributeCollection = $this->reindexAttributeCollection($attributeCollection);
        $this->taxCollection = $taxCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $attributeCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function reindexAttributeCollection(array $attributeCollection)
    {
        $result = [];
        foreach ($attributeCollection as $attributeTransfer) {
            $result[$attributeTransfer->getMetadata()->getKey()] = $attributeTransfer;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[] $metadataCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[]
     */
    protected function reindexAttributeMetadataCollection(array $metadataCollection)
    {
        $result = [];
        foreach ($metadataCollection as $metadataTransfer) {
            $result[$metadataTransfer->getKey()] = $metadataTransfer;
        }

        return $result;
    }

    /**
     * @param int|null $idProductAbstract |null
     *
     * @return mixed
     */
    public function getOptions($idProductAbstract = null)
    {
        $formOptions[ProductFormAdd::ATTRIBUTE_METADATA] = $this->convertAttributeMetadataToOptionValues($this->attributeMetadataCollection);
        $formOptions[ProductFormAdd::ATTRIBUTE_VALUES] = $this->convertAttributeValueToOptionValues($this->attributeCollection);
        $formOptions[ProductFormAdd::TAX_SET] = $this->taxCollection;
        $formOptions[ProductFormAdd::ID_LOCALE] = $this->localeFacade->getCurrentLocale()->getIdLocale();

        return $formOptions;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[] $data
     *
     * @return array
     */
    protected function convertAttributeMetadataToOptionValues(array $data)
    {
        $result = [];
        foreach ($data as $name => $transfer) {
            if (!isset($this->attributeCollection[$name])) {
                continue;
            }

            $attributeTransfer = $this->attributeCollection[$name];
            foreach ($attributeTransfer->getLocalizedAttributes() as $localizedAttribute) {
                if ((int)$localizedAttribute->getFkLocale() === (int)$this->localeFacade->getCurrentLocale()->getIdLocale()) {
                    $name = $localizedAttribute->getName();
                    break;
                }
            }

            $result[$transfer->getKey()] = $name;
        }

        return $result;
    }

    protected function getLocalizedAttributeMetadataKey($keyToLocalize)
    {
        foreach ($this->attributeMetadataCollection as $metadataTransfer) {
            if ($metadataTransfer->getKey() !== $keyToLocalize) {
                continue;
            }

            foreach ($this->attributeCollection as $attributeTransfer) {
                if ($attributeTransfer->getMetadata()->getKey() == $metadataTransfer->getKey()) {
                    foreach ($attributeTransfer->getLocalizedAttributes() as $localizedAttribute) {
                        if ((int)$localizedAttribute->getFkLocale() === (int)$this->localeFacade->getCurrentLocale()
                                ->getIdLocale()
                        ) {
                            return $localizedAttribute->getName();
                        }
                    }
                }
            }
        }

        return $keyToLocalize;
    }


    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $data
     *
     * @return array
     */
    protected function convertAttributeValueToOptionValues(array $data)
    {
        $result = [];
        foreach ($data as $transfer) {
            $result[$transfer->getMetadata()->getKey()] = (array)$transfer->getValues();
        }

        return $result;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributesForAbstractProduct($idProductAbstract = null)
    {
        if ($idProductAbstract === null) {
            return [];
        }

        return $this->productManagementFacade
            ->getProductAttributesByAbstractProductId($idProductAbstract);
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        return [
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::LOCALIZED_ATTRIBUTES => $this->getLocalizedAttributesDefaultFields(),
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
        $attributes = [];
        /* @var ProductManagementAttributeTransfer $attributeTransfer */
        foreach ($this->attributeCollection as $attributeTransfer) {
            $attributes[$attributeTransfer->getMetadata()->getKey()]['value'] = [];
        }

        return $attributes;
    }

    /**
     * @return array
     */
    public function getAttributeMetadataDefaultFields()
    {
        $attributes = [];
        /* @var ProductManagementAttributeTransfer $attributeTransfer */
        foreach ($this->attributeCollection as $attributeTransfer) {
            $attributes[$attributeTransfer->getMetadata()->getKey()]['value'] = false;
        }

        return $attributes;
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
     * @param array $attributes
     *
     * @return array
     */
    protected function convertSelectedAttributeMetadataToFormValues(array $attributes)
    {
        foreach ($this->attributeMetadataCollection as $metadataTransfer) {
            $attributeMetadataCollection[$metadataTransfer->getKey()] = ['value' => null];
        }

        $attributeMetadataCollection = array_keys($attributes) + array_keys($attributeMetadataCollection);

        $values = [];
        foreach ($attributeMetadataCollection as $type) {
            $values[$type]['value'] = array_key_exists($type, $attributes);
        }

        return $values;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function convertSelectedAttributeValuesToFormValues(array $attributes)
    {
        $values = [];
        foreach ($attributes as $key => $value) {
            $values[$key]['value'] = $value;
        }

        foreach ($this->attributeCollection as $attributeTransfer) {
            $key = $attributeTransfer->getMetadata()->getKey();
            if (!array_key_exists($key, $values)) {
                $values[$key]['value'] = [];
            }
        }

        return $values;
    }

}
