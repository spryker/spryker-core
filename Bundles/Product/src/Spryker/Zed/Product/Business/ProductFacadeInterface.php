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

interface ProductFacadeInterface
{

    /**
     * Specification:
     * - Adds product abstract with its concrete variants
     * - Adds product abstract with attributes
     * - Adds product abstract with meta information
     * - Adds product abstract with price
     * - Adds product abstract with tax
     * - Adds product concrete with price
     * - Throws exception if product concrete with same SKU exists
     * - Throws exception if abstract product with same SKU exists
     * - Trigger before and after CREATE plugins
     * - Abstract and concrete products are created but not activated or touched
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * Specification:
     * - Saves product abstract with its concrete variants
     * - Saves product abstract attributes
     * - Saves product abstract meta
     * - Saves product abstract price
     * - Saves product abstract tax
     * - Saves product concrete price
     * - Triggers before and after UPDATE plugins
     * - Throws exception if product concrete with same SKU exists
     * - Throws exception if abstract product with same SKU exists
     * - Abstract and concrete products are updated but not activated or touched
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * Specification:
     * - Adds product abstract attributes
     * - Adds product abstract localized attributes
     * - Adds product abstract meta
     * - Adds product abstract price
     * - Adds product abstract tax
     * - Triggers before and after CREATE plugins
     * - Throws exception if abstract product with same SKU exists
     * - Abstract product is created but not activated or touched
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Saves product abstract attributes
     * - Saves product abstract localized attributes
     * - Saves product abstract meta
     * - Saves product abstract price
     * - Saves product abstract tax
     * - Triggers before and after CREATE plugins
     * - Throws exception if abstract product with same SKU exists
     * - Abstract product is created but not activated or touched
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku);

    /**
     * Specification:
     * - Returns abstract product with attributes
     * - Returns abstract product with localized attributes
     * - Returns abstract product with price
     * - Returns abstract product with tax
     * - Triggers LOAD plugins
     *
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku);

    /**
     * Specification:
     * - Returns abstract product with attributes
     * - Returns abstract product with localized attributes
     * - Returns abstract product with price
     * - Returns abstract product with tax
     * - Triggers LOAD plugins
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function getProductAbstractById($idProductAbstract);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku);

    /**
     * Specification:
     * - Returns class used for product attributes processing
     * - Attributes are taken from product abstract and all product concretes
     *   and merged together according to https://academy.spryker.com/display/PRODUCT/RD+-+Product+Attribute+Management
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributeProcessor($idProductAbstract);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku);

    /**
     * Specification:
     * - Adds concrete product with attributes
     * - Adds concrete product with localized attributes
     * - Adds concrete product with price
     * - Triggers before and after CREATE plugins
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Saves concrete product with attributes
     * - Saves concrete product with localized attributes
     * - Saves concrete product with price
     * - Triggers before and after UPDATE plugins
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Checks if product concrete exists
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
     * - Returns id of concrete product
     *
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

    /**
     * Specification:
     * - Returns concrete product with attributes
     * - Returns concrete product with localized attributes
     * - Returns concrete product with price
     * - Triggers LOAD plugins
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteById($idProduct);

    /**
     * Specification:
     * - Returns concrete product with attributes
     * - Returns concrete product with localized attributes
     * - Returns concrete product with price
     * - Triggers LOAD plugins
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);

    /**
     * Specification:
     * - Returns class used for product attributes processing
     * - Attributes are taken from product abstract and all product concretes
     *   and merged together according to https://academy.spryker.com/display/PRODUCT/RD+-+Product+Attribute+Management
     *
     * @api
     *
     * @param string $abstractSku
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributeProcessorByAbstractSku($abstractSku);

    /**
     * Specification:
     * - Returns concrete product collection with loaded attributes
     * - Returns concrete product collection with loaded price
     * - Returns concrete product collection with loaded stock
     * - Returns concrete product collection with loaded images
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
     * - Checks if the product attribute key exists
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
     * - Returns product attribute key if exists, null otherwise
     *
     * @api
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function getProductAttributeKey($key);

    /**
     * Specification:
     * - Creates new product attribute key
     * - Returns created product attribute key
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
     * - Updates an existing product attribute key
     * - Returns the updated product attribute key
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
     * - Touches as active: product abstract, product attribute map, and product url
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
     * - Touches as in-active: product abstract, product attribute map, and product url
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
     * - Touches as deleted: product abstract, product attribute map, and product url
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
     * - Touches as active: product concrete
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
     * - Touches as in-active: product concrete
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
     * - Touches as deleted: product concrete
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
     * - Creates localized product urls based on product abstract localized attributes name
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstract);

    /**
     * Specification:
     * - Updates localized product urls based on product abstract localized attributes name
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstract);

    /**
     * Specification:
     * - Returns localized product urls based on product abstract localized attributes name
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstract);

    /**
     * Specification:
     * - Deletes all urls belonging to given abstract product
     * - Touches urls as deleted
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstract);

    /**
     * Specification:
     * - Touches the url of the product as active for all available locales.
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
     * - Touches the url of the product as deleted for all available locales.
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
     * - Returns localized product abstract name based on localized attributes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductAbstractName(
        ProductAbstractTransfer $productAbstractTransfer,
        LocaleTransfer $localeTransfer
    );

    /**
     * Specification:
     * - Returns localized product concrete name based on localized attributes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductConcreteName(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    );

    /**
     * Specification:
     * - Activates product concrete
     * - Generates and saves product abstract url
     * - Touches as active product
     * - Touches as active product url
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
     * - Deactivates product concrete
     * - Removes product url
     * - Touches as in-active product
     * - Touches as in-active product url
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deActivateProductConcrete($idProductConcrete);

    /**
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
     * - Generates product variants based on attributes
     *
     * $attributeCollection = Array
     *  (
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
     *          )
     *      )
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array|\Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function generateVariants(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection);

}
