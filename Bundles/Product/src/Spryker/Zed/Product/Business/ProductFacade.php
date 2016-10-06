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
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productManager = $this->getFactory()->createProductAbstractManager();

        return $productManager->createProductAbstract($productAbstractTransfer);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        $productManager = $this->getFactory()->createProductAbstractManager();

        return $productManager->hasProductAbstract($sku);
    }

    /**
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
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributeProcessor($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->getProductAttributeProcessor($idProductAbstract);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductAbstractIdByConcreteSku($sku);
    }

    /**
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
            ->createProductConcreteManager()
            ->getProductConcreteById($idProduct);
    }

    /**
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
     * @api
     *
     * @param string $abstractSku
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributeProcessorByAbstractSku($abstractSku)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->getProductAttributeProcessorByAbstractSku($abstractSku);
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
            ->createProductConcreteManager()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

    /**
     * Specification:
     * - Checks if the product attribute key exists in database or not
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
     * - Returns a product attribute key if exists, null otherwise
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
     * - Creates a new product attribute key entity
     * - Returns the newly created product attribute key
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
     * - Updates an existing product attribute key entity
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
     * TODO: check / implement product activation / deactivation workflow
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
     * TODO: check / implement product activation / deactivation workflow
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
     * TODO: check / implement product deletion workflow*
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
            ->touchProductInactive($idProductAbstract);
    }

    /**
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
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstract)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->deleteProductUrl($productAbstract);
    }

    /**
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
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function activateProductConcrete($idProductConcrete)
    {
         return $this->getFactory()
             ->createProductActivator()
             ->activateProductConcrete($idProductConcrete);
    }

    /**
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function deActivateProductConcrete($idProductConcrete)
    {
        return $this->getFactory()
            ->createProductActivator()
            ->deActivateProductConcrete($idProductConcrete);
    }

}
