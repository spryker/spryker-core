<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

class ProductAttributeGuiToProductAttributeBridge implements ProductAttributeGuiToProductAttributeInterface
{

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct($productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributes($idProductAbstract)
    {
        return $this->productAttributeFacade->getProductAbstractAttributeValues($idProductAbstract);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributeValues($idProduct)
    {
        return $this->productAttributeFacade->getProductAttributeValues($idProduct);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributesForProductAbstract($idProductAbstract)
    {
        return $this->productAttributeFacade->getMetaAttributesForProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct)
    {
        return $this->productAttributeFacade->getMetaAttributesForProduct($idProduct);
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10)
    {
        return $this->productAttributeFacade->suggestKeys($searchText, $limit);
    }

    /**
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes)
    {
        $this->productAttributeFacade->saveAbstractAttributes($idProductAbstract, $attributes);
    }

    /**
     * @param int $idProduct
     * @param array $attributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes)
    {
        $this->productAttributeFacade->saveConcreteAttributes($idProduct, $attributes);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public function extractKeysFromAttributes(array $attributes)
    {
        return $this->productAttributeFacade->extractKeysFromAttributes($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public function extractValuesFromAttributes(array $attributes)
    {
        return $this->productAttributeFacade->extractValuesFromAttributes($attributes);
    }

    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     *
     * @return int
     */
    public function getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText = '')
    {
        return $this->productAttributeFacade->getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText);
    }

    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getAttributeValueSuggestions(
        $idProductManagementAttribute,
        $idLocale,
        $searchText = '',
        $offset = 0,
        $limit = 10
    ) {
        return $this->productAttributeFacade->getAttributeValueSuggestions(
            $idProductManagementAttribute,
            $idLocale,
            $searchText,
            $offset,
            $limit
        );
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function getProductManagementAttribute($idProductManagementAttribute)
    {
        return $this->productAttributeFacade->getProductManagementAttribute($idProductManagementAttribute);
    }

    /**
     * @return array
     */
    public function getAttributeAvailableTypes()
    {
        return $this->productAttributeFacade->getAttributeAvailableTypes();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer) {
        return $this->productAttributeFacade->createProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * @param $attributeKey
     * @param LocaleTransfer $localeTransfer
     *
     * @return LocalizedProductManagementAttributeKeyTransfer|null
     */
    public function findAttributeTranslationByKey($attributeKey, LocaleTransfer $localeTransfer)
    {
        return $this->productAttributeFacade->findAttributeTranslationByKey($attributeKey, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return void
     */
    public function translateProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ) {
        $this->productAttributeFacade->translateProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedAttributeKeys($searchText = '', $limit = 10)
    {
        return $this->productAttributeFacade->suggestUnusedAttributeKeys($searchText, $limit);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function updateProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ) {
        return $this->productAttributeFacade->updateProductManagementAttribute($productManagementAttributeTransfer);
    }

}
