<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Product\Business\ProductBusinessFactory getFactory()
 */
class ProductFacade extends AbstractFacade implements ProductFacadeInterface
{

    /**
     * Specification:
     * - Adds product abstract with its attributes, meta information and concrete variants.
     * - Throws exception if product concrete with same SKU exists.
     * - Throws exception if abstract product with same SKU exists.
     * - Trigger before and after CREATE plugins.
     * - Abstract and concrete products are created but not activated or touched.
     * - Returns the ID of the newly created abstract product.
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
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->addProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * Specification:
     * - Saves product abstract with its concrete variants.
     * - Saves product abstract attributes.
     * - Saves product abstract meta.
     * - Triggers before and after UPDATE plugins.
     * - Throws exception if product concrete with same SKU exists.
     * - Throws exception if abstract product with same SKU exists.
     * - Abstract and concrete products are updated but not activated or touched.
     * - Returns the ID of the abstract product.
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
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->saveProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * Specification:
     * - Adds product abstract attributes.
     * - Adds product abstract localized attributes.
     * - Adds product abstract meta.
     * - Triggers before and after CREATE plugins.
     * - Throws exception if abstract product with same SKU exists.
     * - Abstract product is created but not activated or touched.
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
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->createProductAbstract($productAbstractTransfer);
    }

    /**
     * Specification:
     * - Saves product abstract attributes
     * - Saves product abstract localized attributes
     * - Saves product abstract meta
     * - Updates URL of an active product when it's changed.
     * - Triggers before and after CREATE plugins
     * - Throws exception if abstract product with same SKU exists
     * - Abstract product is created but not activated or touched
     * - Returns the ID of the abstract product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * Specification:
     * - Checks if product abstract exists
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->hasProductAbstract($sku);
    }

    /**
     * Specification:
     * - Returns the ID of an abstract product for the given SKU if it exists, NULL otherwise.
     *
     * @api
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->findProductAbstractIdBySku($sku);
    }

    /**
     * Specification:
     * - Returns abstract product with attributes and localized attributes
     * - Triggers READ plugins
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->findProductAbstractById($idProductAbstract);
    }

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
    public function getAbstractSkuFromProductConcrete($sku)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->getAbstractSkuFromProductConcrete($sku);
    }

    /**
     * Specification:
     * - Returns the abstract product ID of the given concrete product SKU if exists.
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
    public function getProductAbstractIdByConcreteSku($concreteSku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductAbstractIdByConcreteSku($concreteSku);
    }

    /**
     * Specification:
     * - Adds concrete product with attributes and localized attributes.
     * - Throws exception if product concrete with same SKU exists.
     * - Triggers before and after CREATE plugins.
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
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->createProductConcrete($productConcreteTransfer);
    }

    /**
     * Specification:
     * - Saves concrete product with attributes and localized attributes.
     * - Throws exception if product concrete with same SKU exists.
     * - Triggers before and after UPDATE plugins.
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
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->saveProductConcrete($productConcreteTransfer);
    }

    /**
     * Specification:
     * - Checks if product concrete exists.
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->hasProductConcrete($sku);
    }

    /**
     * Specification:
     * - Returns ID of concrete product.
     *
     * @api
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->findProductConcreteIdBySku($sku);
    }

    /**
     * Specification:
     * - Returns concrete product with attributes and localized attributes.
     * - Returns NULL if the concrete product is not found by ID.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->findProductConcreteById($idProduct);
    }

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
    public function getProductConcrete($concreteSku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductConcrete($concreteSku);
    }

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
    public function getConcreteProductsByAbstractProductId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

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
    public function hasProductAttributeKey($key)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->hasAttributeKey($key);
    }

    /**
     * Specification:
     * - Returns product attribute key if exists, NULL otherwise.
     *
     * @api
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function findProductAttributeKey($key)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->findAttributeKey($key);
    }

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
    public function createProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->createAttributeKey($productAttributeKeyTransfer);
    }

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
    public function updateProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->updateAttributeKey($productAttributeKeyTransfer);
    }

    /**
     * Specification:
     * - Touches abstract product and all it's variants.
     * - Touches related "product_abstract", "product_concrete" and "attribute_map" entries.
     * - Used touch event statuses (active, inactive) depends on the current active status of the product and it's variants.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstract($idProductAbstract)
    {
        $this->getFactory()
            ->createProductAbstractTouch()
            ->touchProductAbstract($idProductAbstract);
    }

    /**
     * Specification:
     * - Touches as active: "product_abstract" and "product_attribute_map"
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract)
    {
        $this->getFactory()
            ->createProductAbstractTouch()
            ->touchProductAbstractActive($idProductAbstract);
    }

    /**
     * Specification:
     * - Touches as in-active: "product_abstract" and "product_attribute_map".
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductInactive($idProductAbstract)
    {
        $this->getFactory()
            ->createProductAbstractTouch()
            ->touchProductAbstractInactive($idProductAbstract);
    }

    /**
     * Specification:
     * - Touches as deleted: "product_abstract" and "product_attribute_map".
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductDeleted($idProductAbstract)
    {
        $this->getFactory()
            ->createProductAbstractTouch()
            ->touchProductAbstractDeleted($idProductAbstract);
    }

    /**
     * Specification:
     * - Touches a concrete product.
     * - Touches related "product_concrete", "product_abstract" and "attribute_map" entries.
     * - Used touch event statuses (active, inactive) depends on the current status of the product.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcrete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteTouch()
            ->touchProductConcrete($idProductConcrete);
    }

    /**
     * Specification:
     * - Touches as active: "product_concrete".
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteActive($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteTouch()
            ->touchProductConcreteActive($idProductConcrete);
    }

    /**
     * Specification:
     * - Touches as in-active: "product_concrete".
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteInactive($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteTouch()
            ->touchProductConcreteInactive($idProductConcrete);
    }

    /**
     * Specification:
     * - Touches as deleted: "product_concrete".
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteDelete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteTouch()
            ->touchProductConcreteDeleted($idProductConcrete);
    }

    /**
     * Specification:
     * - Creates localized product URLs based on product abstract localized attributes name.
     * - Executes touch logic for product URL activation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->createProductUrl($productAbstractTransfer);
    }

    /**
     * Specification:
     * - Updates localized product URLs based on product abstract localized attributes name.
     * - Executes touch logic for product URL update.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->updateProductUrl($productAbstractTransfer);
    }

    /**
     * Specification:
     * - Returns localized product URLs for all available locales.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->getProductUrl($productAbstractTransfer);
    }

    /**
     * Specification:
     * - Deletes all URLs belonging to given abstract product.
     * - Executes touch logic for product URL deletion.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->deleteProductUrl($productAbstractTransfer);
    }

    /**
     * Specification:
     * - Touches the URL of the product as active for all available locales.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlActive(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->touchProductAbstractUrlActive($productAbstractTransfer);
    }

    /**
     * Specification:
     * - Touches the URL of the product as deleted for all available locales.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlDeleted(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->touchProductAbstractUrlDeleted($productAbstractTransfer);
    }

    /**
     * Specification:
     * - Returns localized product abstract name based on localized attributes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductAbstractName(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractNameGenerator()
            ->getLocalizedProductAbstractName($productAbstractTransfer, $localeTransfer);
    }

    /**
     * Specification:
     * - Returns localized product concrete name based on localized attributes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteNameGenerator()
            ->getLocalizedProductConcreteName($productConcreteTransfer, $localeTransfer);
    }

    /**
     * Specification:
     * - Activates product concrete.
     * - Generates and saves product abstract URL.
     * - Touches as active product.
     * - Touches as active product URL.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete)
    {
         $this->getFactory()
             ->createProductConcreteActivator()
             ->activateProductConcrete($idProductConcrete);
    }

    /**
     * Specification:
     * - Deactivates product concrete.
     * - Removes product URL.
     * - Touches as in-active product.
     * - Touches as in-active product URL.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteActivator()
            ->deactivateProductConcrete($idProductConcrete);
    }

    /**
     * Specification:
     * - Generatate all possible permutations for given attributes.
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
    public function generateAttributePermutations(array $superAttributes, $idProductConcrete)
    {
        return $this->getFactory()
            ->createAttributePermutationGenerator()
            ->generateAttributePermutations($superAttributes, $idProductConcrete);
    }

    /**
     * Specification:
     * - Generates product variants based on attributes.
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
     * )
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array|\Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function generateVariants(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection)
    {
        return $this->getFactory()
            ->createProductVariantGenerator()
            ->generate($productAbstractTransfer, $attributeCollection);
    }

    /**
     * Specification:
     * - Returns true if any of the concrete products of abstract products are active.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductActive($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAbstractStatusChecker()
            ->isActive($idProductAbstract);
    }

    /**
     * Specification:
     * - Returns an array with attribute keys of a persisted product.
     * - The result is a combination of the abstract product's attribute keys and all its existing concretes' attribute keys.
     * - If $localeTransfer is provided then localized abstract and concrete attribute keys are also part of the result.
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
    public function getCombinedAbstractAttributeKeys(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createAttributeLoader()
            ->getCombinedAbstractAttributeKeys($productAbstractTransfer, $localeTransfer);
    }

    /**
     * Specification:
     * - Returns an associative array of attribute key - attribute value pairs of a persisted concrete product.
     * - The result is a combination of the concrete's attributes and its abstract's attributes.
     * - If $localeTransfer is provided then localized concrete and abstract attributes are also part of the result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedConcreteAttributes(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createAttributeLoader()
            ->getCombinedConcreteAttributes($productConcreteTransfer, $localeTransfer);
    }

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
    public function combineRawProductAttributes(RawProductAttributesTransfer $rawProductAttributesTransfer)
    {
        return $this->getFactory()
            ->createAttributeMerger()
            ->merge($rawProductAttributesTransfer);
    }

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
    public function encodeProductAttributes(array $attributes)
    {
        return $this->getFactory()
            ->createAttributeEncoder()
            ->encodeAttributes($attributes);
    }

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
    public function decodeProductAttributes($attributes)
    {
        return $this->getFactory()
            ->createAttributeEncoder()
            ->decodeAttributes($attributes);
    }

    /**
     * Specification:
     * - Saves additional product information like images and super attributes to sales order item tables
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSalesOrderProductInformation(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createMetadataManager()->saveOrderInformation($quoteTransfer, $checkoutResponse);
    }

    /**
     * Specification
     * - Hydrates an OrderTransfer with information about the product from the sales order item tables
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateSalesOrderProductInformation(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createMetadataManager()->hydrateOrderInformation($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSuperAttributeMetadata(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createSuperAttributeManager()->saveSuperAttributeMetadata($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateSuperAttributeMetadata(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createSuperAttributeManager()->hydrateSuperAttributeMetadata($orderTransfer);
    }

    /**
     * Specification:
     * - Hydrates product ids (abstract / concrete) into an order based on their sku
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductIds(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createProductIdHydrator()->hydrateProductIds($orderTransfer);
    }

}
