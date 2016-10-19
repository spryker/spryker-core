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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Product\Business\ProductBusinessFactory getFactory()
 */
class ProductFacade extends AbstractFacade implements ProductFacadeInterface
{

    /**
     * Specification:
     * - Adds product abstract with its concrete variants
     * - Adds product abstract with attributes
     * - Adds product abstract with meta information
     * - Adds product abstract with price
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
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->addProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * Specification:
     * - Saves product abstract with its concrete variants
     * - Saves product abstract attributes
     * - Saves product abstract meta
     * - Saves product abstract price
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
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->saveProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * Specification:
     * - Adds product abstract attributes
     * - Adds product abstract localized attributes
     * - Adds product abstract meta
     * - Adds product abstract price
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
     * - Saves product abstract price
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
     * - Returns abstract product with attributes
     * - Returns abstract product with localized attributes
     * - Returns abstract product with price
     * - Triggers READ plugins
     *
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->getProductAbstractIdBySku($sku);
    }

    /**
     * Specification:
     * - Returns abstract product with attributes
     * - Returns abstract product with localized attributes
     * - Returns abstract product with price
     * - Triggers READ plugins
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
            ->createProductAbstractManager()
            ->getProductAbstractById($idProductAbstract);
    }

    /**
     * @api
     *
     * @param string $sku
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
    public function getProductAttributeProcessor($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAttributeProcessor($idProductAbstract);
    }

    /**
     * Specification:
     * - Finds product abstract based on product concrete SKU and returns product abstract ID
     *
     * @api
     *
     * @param string $concreteSku
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
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->createProductConcrete($productConcreteTransfer);
    }

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
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->saveProductConcrete($productConcreteTransfer);
    }

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
    public function hasProductConcrete($sku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->hasProductConcrete($sku);
    }

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
    public function getProductConcreteIdBySku($sku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductConcreteIdBySku($sku);
    }

    /**
     * Specification:
     * - Returns concrete product with attributes
     * - Returns concrete product with localized attributes
     * - Returns concrete product with price
     * - Triggers READ plugins
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
            ->createProductConcreteManager()
            ->getProductConcreteById($idProduct);
    }

    /**
     * Specification:
     * - Returns concrete product with attributes
     * - Returns concrete product with localized attributes
     * - Returns concrete product with price
     * - Triggers READ plugins
     *
     * @api
     *
     * @param string $concreteSku
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
    public function getProductAttributeProcessorByAbstractSku($abstractSku)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAttributeProcessorByAbstractSku($abstractSku);
    }

    /**
     * Specification:
     * - Returns concrete product collection
     * - Triggers READ plugins
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
     * - Checks if the product attribute key exists
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
     * - Returns product attribute key if exists, null otherwise
     *
     * @api
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function getProductAttributeKey($key)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->getAttributeKey($key);
    }

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
    public function createProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->createAttributeKey($productAttributeKeyTransfer);
    }

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
    public function updateProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->updateAttributeKey($productAttributeKeyTransfer);
    }

    /**
     * Specification:
     * - Touches as active: product abstract and product attribute map
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
            ->createProductAbstractManager()
            ->touchProductActive($idProductAbstract);
    }

    /**
     * Specification:
     * - Touches as in-active: product abstract and product attribute map
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
            ->createProductAbstractManager()
            ->touchProductInactive($idProductAbstract);
    }

    /**
     * Specification:
     * - Touches as deleted: product abstract and product attribute map
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
            ->createProductAbstractManager()
            ->touchProductDeleted($idProductAbstract);
    }

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
    public function touchProductConcreteActive($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteManager()
            ->touchProductActive($idProductConcrete);
    }

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
    public function touchProductConcreteInactive($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteManager()
            ->touchProductInactive($idProductConcrete);
    }

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
    public function touchProductConcreteDelete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteManager()
            ->touchProductDeleted($idProductConcrete);
    }

    /**
     * Specification:
     * - Creates localized product urls based on product abstract localized attributes name
     * - Executes touch logic for product url activation
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstract)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->createProductUrl($productAbstract);
    }

    /**
     * Specification:
     * - Updates localized product urls based on product abstract localized attributes name
     * - Executes touch logic for product url update
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstract)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->updateProductUrl($productAbstract);
    }

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
    public function getProductUrl(ProductAbstractTransfer $productAbstract)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->getProductUrl($productAbstract);
    }

    /**
     * Specification:
     * - Deletes all urls belonging to given abstract product
     * - Executes touch logic for product url deletion
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return void
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstract)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->deleteProductUrl($productAbstract);
    }

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
    public function touchProductAbstractUrlActive(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->touchProductAbstractUrlActive($productAbstractTransfer);
    }

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
    public function touchProductAbstractUrlDeleted(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->touchProductAbstractUrlDeleted($productAbstractTransfer);
    }

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
    public function getLocalizedProductAbstractName(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->getLocalizedProductAbstractName($productAbstractTransfer, $localeTransfer);
    }

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
    public function getLocalizedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getLocalizedProductConcreteName($productConcreteTransfer, $localeTransfer);
    }

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
    public function activateProductConcrete($idProductConcrete)
    {
         $this->getFactory()
             ->createProductActivator()
             ->activateProductConcrete($idProductConcrete);
    }

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
    public function deactivateProductConcrete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductActivator()
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
     * - Returns true if any of the concrete products of abstract products are active
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
            ->createProductManager()
            ->isProductActive($idProductAbstract);
    }

}
