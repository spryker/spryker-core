<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Spryker\Shared\Product\ProductConstants;

interface ProductFacadeInterface
{
    /**
     * Specification:
     * - Adds abstract product with its attributes, meta data, and concrete products.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Throws exception if an abstract product with the same SKU exists.
     * - Triggers "before" and "after" CREATE plugins.
     * - Returns the ID of the newly created abstract product.
     * - Does not activate or touche created abstract and concrete products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * Specification:
     * - Saves abstract product with its concrete products.
     * - Saves abstract product attributes.
     * - Saves abstract product meta data.
     * - Triggers "before" and "after" UPDATE plugins.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Throws exception if an abstract product with the same SKU exists.
     * - Returns the ID of the abstract product.
     * - Does not activate or touche updated abstract and concrete products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * Specification:
     * - Adds abstract product attributes.
     * - Adds abstract product localized attributes.
     * - Adds abstract product meta data.
     * - Triggers "before" and "after" CREATE plugins.
     * - Throws exception if an abstract product with the same SKU exists.
     * - Returns the ID of the newly created abstract product.
     * - Does not activate or touche created abstract product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Saves abstract product attributes.
     * - Saves abstract product localized attributes.
     * - Saves abstract product meta data.
     * - Updates the URL of an active abstract product if it is changed.
     * - Triggers "before" and "after" CREATE plugins.
     * - Throws exception if an abstract product with the same SKU exists.
     * - Does not activate or touche created abstract product.
     * - Returns the ID of the newly created abstract product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Checks if the abstract product exists.
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku);

    /**
     * Specification:
     * - Returns the ID of an abstract product for the given SKU if it exists, null otherwise.
     *
     * @api
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku);

    /**
     * Specification:
     * - Returns an abstract product with attributes and localized attributes.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract);

    /**
     * Specification:
     * - Filters abstract products by specified SKU.
     *
     * @api
     *
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractBySku(string $sku, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;

    /**
     * Specification:
     * - Filters abstract products by specified localized name.
     *
     * @api
     *
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractByLocalizedName(string $localizedName, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;

    /**
     * Specification:
     * - Filters concrete products by specified SKU.
     *
     * @api
     *
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function filterProductConcreteBySku(string $sku, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;

    /**
     * Specification:
     * - Filters concrete products by specified localized name.
     *
     * @api
     *
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function filterProductConcreteByLocalizedName(string $localizedName, int $limit = ProductConstants::FILTERED_PRODUCTS_LIMIT_DEFAULT): array;

    /**
     * Specification:
     * - Returns the SKU of an abstract product that belongs to the given SKU of a concrete product.
     * - Throws exception if no abstract product is found.
     *
     * @api
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku);

    /**
     * Specification:
     * - Returns the abstract product ID of the given concrete product SKU if it exists.
     * - Throws exception if no abstract product is found.
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($concreteSku);

    /**
     * Specification:
     * - Adds concrete product with attributes and localized attributes.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Triggers "before" and "after" CREATE plugins.
     * - Returns the ID of the newly created concrete product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Saves concrete product with attributes and localized attributes.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Triggers "before" and "after" UPDATE plugins.
     * - Returns the ID of the concrete product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Checks if the concrete product exists.
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku);

    /**
     * Specification:
     * - Returns the ID of the concrete product.
     *
     * @api
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku);

    /**
     * Specification:
     * - Returns the concrete product with attributes and localized attributes.
     * - Returns null if the concrete product is not found by ID.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct);

    /**
     * Specification:
     * - Returns concrete product with attributes and localized attributes.
     * - Throws exception if the concrete product is not found by SKU.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);

    /**
     * Specification:
     * - Returns concrete product collection.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract);

    /**
     * Specification:
     * - Checks if the product attribute key exists.
     *
     * @api
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasProductAttributeKey($key);

    /**
     * Specification:
     * - Returns the product attribute key if it exists, null otherwise.
     *
     * @api
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function findProductAttributeKey($key);

    /**
     * Specification:
     * - Creates new product attribute key.
     * - Returns created product attribute key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function createProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer);

    /**
     * Specification:
     * - Updates an existing product attribute key.
     * - Returns the updated product attribute key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function updateProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer);

    /**
     * Specification:
     * - Touches the abstract product and all of its concrete products.
     * - Touches related "product_abstract", "product_concrete", and "attribute_map" entries.
     * - Used touch event statuses (active, inactive) depend on the current status of the abstract product and its concrete products.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Touches "product_abstract" and "product_attribute_map" as active.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract);

    /**
     * Specification:
     * - Touches "product_abstract" and "product_attribute_map" as in-active.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductInactive($idProductAbstract);

    /**
     * Specification:
     * - Touches "product_abstract" and "product_attribute_map" as deleted.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductDeleted($idProductAbstract);

    /**
     * Specification:
     * - Touches a concrete product.
     * - Touches related "product_concrete", "product_abstract", and "attribute_map" entries.
     * - Used touch event statuses (active, inactive) depend on the current status of the concrete product.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcrete($idProductConcrete);

    /**
     * Specification:
     * - Touches "product_concrete" as active.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteActive($idProductConcrete);

    /**
     * Specification:
     * - Touches "product_concrete" as in-active.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteInactive($idProductConcrete);

    /**
     * Specification:
     * - Touches "product_concrete" as deleted.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteDelete($idProductConcrete);

    /**
     * Specification:
     * - Creates localized abstract product URLs based on abstract product localized attributes name.
     * - Executes touch logic for abstract product URL activation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Updates localized abstract product URLs based on abstract product localized attributes name.
     * - Executes touch logic for abstract product URL update.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Returns localized abstract product URLs for all available locales.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Deletes all URLs belonging to the given abstract product.
     * - Executes touch logic for abstract product URL deletion.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Touches the URL of the abstract product for all available locales as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlActive(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Touches the URL of the abstract product for all available locales as deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlDeleted(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Returns localized abstract product name based on localized attributes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductAbstractName(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - Returns localized concrete product name based on localized attributes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - Activates concrete product.
     * - Generates and saves the related abstract product URL.
     * - Touches concrete product as active.
     * - Touches abstract product URL as active.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete);

    /**
     * Specification:
     * - Deactivates concrete product.
     * - Touches concrete product as active.
     * - Deletes abstract product URL if abstract product is inactive.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete);

    /**
     * Specification:
     * - Generates all possible permutations of the given attributes.
     *
     * Leaf node of a tree is concrete id.
     * (
     *   [color:red] => array (
     *       [brand:nike] => array(
     *          [id] => 1
     *       )
     *   ),
     *   [brand:nike] => array(
     *       [color:red] => array(
     *          [id] => 1
     *       )
     *   )
     * )
     *
     * @api
     *
     * @param array $superAttributes
     * @param int $idProductConcrete
     *
     * @return array
     */
    public function generateAttributePermutations(array $superAttributes, $idProductConcrete);

    /**
     * Specification:
     * - Generates concrete products based on attributes.
     *
     * Expected input AttributeCollection structure:
     * (
     *     [color] => Array
     *      (
     *          [red] => Red
     *          [blue] => Blue
     *      )
     *     [flavour] => Array
     *      (
     *          [sweet] => Cakes
     *      )
     *     [size] => Array
     *      (
     *          [40] => 40
     *          [41] => 41
     *          [42] => 42
     *      )
     * )
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function generateVariants(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection);

    /**
     * Specification:
     * - Returns true if any of the concrete products of the abstract product is active.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductActive($idProductAbstract);

    /**
     * Specification:
     * - Returns the attribute keys of the abstract product and its concrete products.
     * - Includes localized abstract product and concrete products attribute keys when $localeTransfer is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return array
     */
    public function getCombinedAbstractAttributeKeys(ProductAbstractTransfer $productAbstractTransfer, ?LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Returns an array of productIds as keys with array of attribute keys as values of a persisted products.
     * - The attribute keys is a combination of the abstract product's attribute keys and all its existing concretes' attribute keys.
     * - If $localeTransfer is provided then localized abstract and concrete attribute keys are also part of the result.
     *
     * @api
     *
     * @param int[] $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return array
     */
    public function getCombinedAbstractAttributeKeysForProductIds($productIds, ?LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Returns an associative array of attribute key - attribute value pairs of the persisted concrete product.
     * - The result is a combination of the concrete product's attributes and its abstract product's attributes.
     * - Includes localized abstract product and concrete products attribute keys when $localeTransfer is provided.

     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedConcreteAttributes(ProductConcreteTransfer $productConcreteTransfer, ?LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Returns an associative array of attribute key - attribute value pairs.
     * - The result is the correct inheritance combination of the provided raw product attribute data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RawProductAttributesTransfer $rawProductAttributesTransfer
     *
     * @return array
     */
    public function combineRawProductAttributes(RawProductAttributesTransfer $rawProductAttributesTransfer);

    /**
     * Specification:
     * - Encodes an array of product attribute key - attribute value pairs to JSON string.
     *
     * @api
     *
     * @param array $attributes
     *
     * @return string
     */
    public function encodeProductAttributes(array $attributes);

    /**
     * Specification:
     * - Decodes product attributes JSON string to an array of attribute key - attribute value pairs.
     *
     * @api
     *
     * @param string $attributes
     *
     * @return array
     */
    public function decodeProductAttributes($attributes);
}
