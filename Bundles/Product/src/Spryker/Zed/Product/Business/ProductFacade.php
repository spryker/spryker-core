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
        $productManager = $this->getFactory()->createProductManager();

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
        $productManager = $this->getFactory()->createProductManager();

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
        return $this->getFactory()->createProductManager()->getProductAbstractIdBySku($sku);
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
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku)
    {
        return $this->getFactory()->createProductManager()->getProductAbstractIdByConcreteSku($sku);
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
        return $this->getFactory()->createProductManager()->getAbstractSkuFromProductConcrete($sku);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributesByAbstractProductId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductManager()
            ->getProductAttributesByAbstractProductId($idProductAbstract);
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
        $productManager = $this->getFactory()->createProductManager();

        return $productManager->createProductConcrete($productConcreteTransfer);
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
        $productManager = $this->getFactory()->createProductManager();

        return $productManager->hasProductConcrete($sku);
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
        return $this->getFactory()->createProductManager()->getProductConcreteIdBySku($sku);
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
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku)
    {
        return $this->getFactory()->createProductManager()->getProductConcrete($concreteSku);
    }

    /**
     * @api
     *
     * @param string $abstractSku
     *
     * @return \Generated\Shared\Transfer\ProductVariantTransfer[]
     */
    public function getProductVariantsByAbstractSku($abstractSku)
    {
        //TODO FIX ME
        return $this->getFactory()
           ->createProductManager()
           ->getProductVariantsByAbstractSku($abstractSku);
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
        $productManager = $this->getFactory()->createProductManager();

        $productManager->touchProductActive($idProductAbstract);
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
        $productManager = $this->getFactory()->createProductManager();

        $productManager->touchProductInactive($idProductAbstract);
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
        $productManager = $this->getFactory()->createProductManager();

        $productManager->touchProductDeleted($idProductAbstract);
    }

    /**
     * @api
     *
     * @param $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteActive($idProductConcrete)
    {
        $this->getFactory()->createProductConcreteManager()->touchProductActive($idProductConcrete);
    }

    /**
     * @api
     *
     * @param $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteInactive($idProductConcrete)
    {
        $this->getFactory()->createProductConcreteManager()->touchProductInactive($idProductConcrete);
    }

    /**
     * @api
     *
     * @param $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteDelete($idProductConcrete)
    {
        $this->getFactory()->createProductConcreteManager()->touchProductDeleted($idProductConcrete);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createProductUrl($sku, $url, LocaleTransfer $locale)
    {
        return $this->getFactory()->createProductManager()->createProductUrl($sku, $url, $locale);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createAndTouchProductUrl($sku, $url, LocaleTransfer $locale)
    {
        return $this->getFactory()->createProductManager()->createAndTouchProductUrl($sku, $url, $locale);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createAndTouchProductUrlByIdProduct($idProductAbstract, $url, LocaleTransfer $locale)
    {
        return $this->getFactory()->createProductManager()->createAndTouchProductUrlByIdProduct($idProductAbstract, $url, $locale);
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
         return $this->getFactory()->createProductConcreteActivator()->activateProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function createAndTouchProductUrls($idProductAbstract)
    {
        $this->getFactory()->createProductUrlGenerator()->createAndTouchProductUrls($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return bool
     */
    public function deActivateProductConcrete($idProductConcrete)
    {
        return $this->getFactory()->createProductConcreteActivator()->deActivateProductConcrete($idProductConcrete);
    }

}
