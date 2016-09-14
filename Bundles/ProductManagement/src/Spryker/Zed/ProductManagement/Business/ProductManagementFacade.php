<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementBusinessFactory getFactory()
 */
class ProductManagementFacade extends AbstractFacade implements ProductManagementFacadeInterface
{

    /**
     * Specification:
     * - Add product abstract with its concrete variants
     * - Add product abstract attributes information
     * - Add product abstract meta information
     * - Add product abstract images information
     * - Add concrete product stock information
     * - Add product abstract price information
     * - Add product abstract tax information
     * - Generates concrete products based on variant attributes
     * - Throws exception if abstract product with same SKU exists
     * - Abstract and concrete products are created but not activated or touched
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->addProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * Specification:
     * - Save product abstract with its concrete variants
     * - Save product abstract attributes information
     * - Save product abstract meta information
     * - Save product abstract images information
     * - Save concrete product stock information
     * - Save product abstract price information
     * - Save product abstract tax information
     * - Throws exception if product with same SKU exists
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->saveProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * Specification:
     * - Returns abstract product transfer with loaded attributes
     * - Returns abstract product transfer with loaded price
     * - Returns abstract product transfer with loaded tax
     * - Returns abstract product transfer with loaded images
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function getProductAbstractById($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAbstractById($idProductAbstract);
    }

    /**
     * Specification:
     * - Returns concrete product transfer with loaded attributes
     * - Returns concrete product transfer with loaded price
     * - Returns concrete product transfer with loaded stock
     * - Returns concrete product transfer with loaded images
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteById($idProduct)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductConcreteById($idProduct);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributesByAbstractProductId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAttributesByAbstractProductId($idProductAbstract);
    }

    /**
     * Specification:
     * - Returns concrete product transfer collection with loaded attributes
     * - Returns concrete product transfer collection with loaded price
     * - Returns concrete product transfer collection with loaded stock
     * - Returns concrete product transfer collection with loaded images
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        return $this->getFactory()
            ->createAttributeManager()
            ->getProductAttributeCollection();
    }

    /**
     * Specification:
     * - Searches for an existing product attribute key entity by the provided key in database or create it if does not exist
     * - Creates a new product management attribute entity with the given data and the found/created attribute key entity
     * - Creates a glossary key for the product attribute key with the configured prefix if does not exist already
     * - Saves predefined product attribute values if provided
     * - Returns a transfer that also contains the ids of the created entities
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
     * Specification:
     * - Searches for an existing product attribute key entity in database by the provided key or create it if does not exist
     * - Updates an existing product management attribute entity by id with the given data and the found/created attribute key entity
     * - Creates a glossary key for the product attribute key with the configured prefix if does not exist already
     * - Saves predefined product attribute values if provided
     * - Removes old predefined product attribute values which were persisted earlier but are not used any more
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
     * Specification:
     * - Saves product attribute key translation to the glossary
     * - Saves predefined attribute value translations if provided
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
     * Specification:
     * - Reads a product management attribute entity from the database and returns a fully hydrated transfer representation
     * - Return null if the entity is not found by id
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
     * Specification:
     * - Returns a filtered list of keys that exists in the persisted product attribute key list but not in the persisted
     * product management attribute list
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

}
