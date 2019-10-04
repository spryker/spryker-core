<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface getRepository()
 */
class ProductAttributeFacade extends AbstractFacade implements ProductAttributeFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getProductAbstractAttributes($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributeValues($idProduct)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getProductAttributeValues($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributesForProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getMetaAttributesForProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getMetaAttributesForProduct($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10)
    {
        return $this->getFactory()
            ->createProductAttributeReader()
            ->suggestKeys($searchText, $limit);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes)
    {
        $this->getFactory()
            ->createProductAttributeWriter()
            ->saveAbstractAttributes($idProductAbstract, $attributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param array $attributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes)
    {
        $this->getFactory()
            ->createProductAttributeWriter()
            ->saveConcreteAttributes($idProduct, $attributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $attributes
     *
     * @return array
     */
    public function extractKeysFromAttributes(array $attributes)
    {
        return $this->getFactory()
            ->createProductAttributeMapper()
            ->extractKeysFromAttributes($attributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $attributes
     *
     * @return array
     */
    public function extractValuesFromAttributes(array $attributes)
    {
        return $this->getFactory()
            ->createProductAttributeMapper()
            ->extractValuesFromAttributes($attributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        return $this->getFactory()
            ->createAttributeWriter()
            ->createProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function updateProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        return $this->getFactory()
            ->createAttributeWriter()
            ->updateProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductManagementAttribute
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function getProductManagementAttribute($idProductManagementAttribute)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->getAttribute($idProductManagementAttribute);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return void
     */
    public function translateProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $this->getFactory()
            ->createAttributeTranslator()
            ->saveProductManagementAttributeTranslation($productManagementAttributeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     *
     * @return int
     */
    public function getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText = '')
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getAttributeValueSuggestions($idProductManagementAttribute, $idLocale, $searchText = '', $offset = 0, $limit = 10)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->getAttributeValueSuggestions($idProductManagementAttribute, $idLocale, $searchText, $offset, $limit);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getAttributeAvailableTypes()
    {
        return $this->getFactory()
            ->getConfig()
            ->getAttributeAvailableTypes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $attributeKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer|null
     */
    public function findAttributeTranslationByKey($attributeKey, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createAttributeTranslationReader()
            ->findAttributeTranslationByKey($attributeKey, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedAttributeKeys($searchText = '', $limit = 10)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->suggestUnusedKeys($searchText, $limit);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->getProductAttributeCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getUniqueSuperAttributesFromConcreteProducts(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createSuperAttributeReader()
            ->getUniqueSuperAttributesFromConcreteProducts($productConcreteTransfers);
    }
}
