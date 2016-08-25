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
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

/**
 * @method \Spryker\Zed\Product\Business\ProductBusinessFactory getFactory()
 */
class ProductFacade extends AbstractFacade implements ProductFacadeInterface
{

    /**
     * @api
     *
     * @param \SplFileInfo $file
     *
     * @return \Spryker\Zed\Product\Business\Model\ProductBatchResult
     */
    public function importProductsFromFile(\SplFileInfo $file)
    {
        return $this->getFactory()
            ->createProductImporter()
            ->importFile($file);
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
     * @param string $attributeName
     *
     * @return bool
     * 
     * TODO: remove
     */
    public function hasAttribute($attributeName)
    {
        $attributeManager = $this->getFactory()->createAttributeManager();

        return $attributeManager->hasAttribute($attributeName);
    }

    /**
     * @api
     *
     * @param string $attributeType
     *
     * @return bool
     *
     * TODO: remove
     */
    public function hasAttributeType($attributeType)
    {
        $attributeManager = $this->getFactory()->createAttributeManager();

        return $attributeManager->hasAttributeType($attributeType);
    }

    /**
     * @api
     *
     * @param string $name
     * @param string $inputType
     * @param int|null $fkParentAttributeType
     *
     * @return int
     *
     * TODO: remove
     */
    public function createAttributeType($name, $inputType, $fkParentAttributeType = null)
    {
        $attributeManager = $this->getFactory()->createAttributeManager();

        return $attributeManager->createAttributeType($name, $inputType, $fkParentAttributeType);
    }

    /**
     * @api
     *
     * @param string $attributeName
     * @param string $attributeType
     * @param bool $isEditable
     *
     * @return int
     *
     * TODO: remove
     */
    public function createAttribute($attributeName, $attributeType, $isEditable = true)
    {
        $attributeManager = $this->getFactory()->createAttributeManager();

        return $attributeManager->createAttribute($attributeName, $attributeType, $isEditable);
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
    public function hasProductConcrete($sku)
    {
        $productManager = $this->getFactory()->createProductManager();

        return $productManager->hasProductConcrete($sku);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param int $idProductAbstract
     *
     * @return int
     */
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer, $idProductAbstract)
    {
        $productManager = $this->getFactory()->createProductManager();

        return $productManager->createProductConcrete($productConcreteTransfer, $idProductAbstract);
    }

    /**
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
     * @deprecated
     *
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface|null $messenger
     *
     * @return void
     *
     * TODO: remove me
     */
    public function install(MessengerInterface $messenger = null)
    {
        $this->getFactory()->createInstaller($messenger)->install();
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
     * @param string $abstractSku
     *
     * @return \Generated\Shared\Transfer\ProductVariantTransfer[]
     */
    public function getProductVariantsByAbstractSku($abstractSku)
    {
        return $this->getFactory()
           ->createProductVariantBuilder()
           ->getProductVariantsByAbstractSku($abstractSku);
    }

    /**
     * @api
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface
     * 
     * TODO: remove this method, because models must not be given away for other bundles
     */
    public function getAttributeManager()
    {
        return $this->getFactory()->createAttributeManager();
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

}
