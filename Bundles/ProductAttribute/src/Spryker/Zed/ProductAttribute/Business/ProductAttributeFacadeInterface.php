<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

/**
 * @method \Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 */
interface ProductAttributeFacadeInterface
{
    /**
     * Specification:
     * - Returns product abstract attributes with metadata info
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract);

    /**
     * Specification:
     * - Returns list of all product concrete attributes
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributeValues($idProduct);

    /**
     * Specification:
     * - Returns list of attributes metadata based on product abstract attributes
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributesForProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Returns list of attributes metadata based on product abstract attributes
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct);

    /**
     * Specification:
     * - Returns list of product keys suggested based on $searchText, super attributes are ignored
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10);

    /**
     * Specification:
     * - Save product abstract attributes
     *
     * $attributes format
     * [
     *   [_] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes);

    /**
     * Specification:
     * - Save product concrete attributes
     *
     * $attributes format
     * [
     *   [_] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @api
     *
     * @see ProductAttributeConfig::DEFAULT_LOCALE
     *
     * @param int $idProduct
     * @param array $attributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes);

    /**
     * Specification:
     * - Return list of unique attribute keys
     *
     * $attributes format
     * [
     *   [_] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @api
     *
     * @see ProductAttributeConfig::DEFAULT_LOCALE
     *
     * @param array $attributes
     *
     * @return array
     */
    public function extractKeysFromAttributes(array $attributes);

    /**
     * Specification:
     * - Return list of unique attribute values
     *
     * $attributes format
     * [
     *   [_] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @api
     *
     * @see ProductAttributeConfig::DEFAULT_LOCALE
     *
     * @param array $attributes
     *
     * @return array
     */
    public function extractValuesFromAttributes(array $attributes);

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
    public function createProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    );

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
    public function updateProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    );

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
    public function getProductManagementAttribute($idProductManagementAttribute);

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
    public function translateProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    );

    /**
     * Specification:
     * - Gets total count of attribute suggestions
     *
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     *
     * @return int
     */
    public function getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText = '');

    /**
     * Specification:
     * - Reads available attributes and gives suggestions by specified parameters
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
    public function getAttributeValueSuggestions(
        $idProductManagementAttribute,
        $idLocale,
        $searchText = '',
        $offset = 0,
        $limit = 10
    );

    /**
     * Specification:
     * - Provides available (supported) attribute types (e.g. number, text, date).
     *
     * @api
     *
     * @return array
     */
    public function getAttributeAvailableTypes();

    /**
     * Specification:
     * - Finds attribute and its translation for passed locale
     * - Returns NULL in case the combination is not found
     *
     * @api
     *
     * @param string $attributeKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer|null
     */
    public function findAttributeTranslationByKey($attributeKey, LocaleTransfer $localeTransfer);

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
    public function suggestUnusedAttributeKeys($searchText = '', $limit = 10);

    /**
     * Specification:
     * - Returns list of ALL product management attributes
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection();

    /**
     * Specification:
     * - Retrieve a list of unique super attributes from concrete product transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getUniqueSuperAttributesFromConcreteProducts(array $productConcreteTransfers): array;
}
