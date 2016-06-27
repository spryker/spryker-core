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
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
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
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface
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
     * @var \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface
     */
    protected $productManagementFacade;

    /**
     * @var array
     */
    protected $attributeCollection = [];

    /**
     * @var array
     */
    protected $attributeValueCollection = [];


    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductQueryContainerInterface $productQueryContainer,
        ProductFacadeInterface $productFacade,
        ProductManagementFacadeInterface $productManagementFacade,
        ProductManagementToLocaleInterface $localeFacade,
        array $attributeCollection,
        array $attributeValueCollection
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productQueryContainer = $productQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
        $this->productManagementFacade = $productManagementFacade;
        $this->locale = $localeFacade->getCurrentLocale();
        $this->attributeCollection = $attributeCollection;
        $this->attributeValueCollection = $attributeValueCollection;
    }

    /**
     * @param int|null $idProductAbstract|null
     *
     * @return mixed
     */
    public function getOptions($idProductAbstract = null)
    {
        //$attributes = $this->getAttributesForAbstractProduct($idProductAbstract);
        //$attributes = $this->convertAttributesToOptionValues($attributes);

        //sd($attributes, $this->attributeCollection);

        $formOptions[ProductFormAdd::ATTRIBUTE_VALUES] = [];
        $formOptions[ProductFormAdd::ATTRIBUTE_VALUES] = array_merge($formOptions[ProductFormAdd::ATTRIBUTE_VALUES], $this->attributeValueCollection);

        $formOptions[ProductFormAdd::ATTRIBUTES] = $this->attributeCollection;

        s($formOptions);

        return $formOptions;
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
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributeValues($idProductAbstract = null)
    {
        if ($idProductAbstract === null) {
            return [];
        }

        $attributeCollection = $this->getAttributesForAbstractProduct($idProductAbstract);

        return $this->convertAttributesToFormValues($attributeCollection);
    }

    /**
     * @param array $attributeCollection
     *
     * @return array
     */
    public function convertAttributesToFormValues(array $attributeCollection)
    {
        $result = [];
        foreach ($attributeCollection as $type => $valueSet) {
            $result[$type]['value'] = $valueSet;
        }

        return $result;
    }

    /**
     * @param array $attributeCollection
     *
     * @return array
     */
    public function convertAttributesToOptionValues(array $attributeCollection)
    {
        $result = [];
        foreach ($attributeCollection as $type => $valueSet) {
            $valueSet = is_array($valueSet) ? $valueSet : [$type => $valueSet];
            $result[$type] = $valueSet;
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getDefaultFormFields()
    {
        return [
            ProductFormAdd::FIELD_SKU => null,
            ProductFormAdd::LOCALIZED_ATTRIBUTES => $this->getLocalizedAttributesDefaultFields(),
            ProductFormAdd::ATTRIBUTES => $this->getAttributesDefaultFields(),
            ProductFormAdd::ATTRIBUTE_VALUES => $this->getAttributeValuesDefaultFields()
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
    public function getAttributesDefaultFields()
    {
        return $this->attributeCollection;
        sd($this->attributeCollection);
        $result = [];
        foreach ($this->attributeCollection as $type => $valueSet) {
            $result[$type]['value'] = [];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAttributeValuesDefaultFields()
    {
        $result = [];
        foreach ($this->attributeValueCollection as $type => $valueSet) {
            $result[$type]['value'] = [];
        }

        return $result;
    }

}
