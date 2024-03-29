<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductManagementToProductBridge implements ProductManagementToProductInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasProductAttributeKey($key): bool
    {
        return $this->productFacade->hasProductAttributeKey($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function findProductAttributeKey($key): ?ProductAttributeKeyTransfer
    {
        return $this->productFacade->findProductAttributeKey($key);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function createProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer): ProductAttributeKeyTransfer
    {
        return $this->productFacade->createProductAttributeKey($productAttributeKeyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function updateProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer): ProductAttributeKeyTransfer
    {
        return $this->productFacade->updateProductAttributeKey($productAttributeKeyTransfer);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku): bool
    {
        return $this->productFacade->hasProductAbstract($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku): bool
    {
        return $this->productFacade->hasProductConcrete($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteCollection
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection): int
    {
        return $this->productFacade->addProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteCollection
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection): int
    {
        return $this->productFacade->saveProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract): ?ProductAbstractTransfer
    {
        return $this->productFacade->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct): ?ProductConcreteTransfer
    {
        return $this->productFacade->findProductConcreteById($idProduct);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract): array
    {
        return $this->productFacade->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete): void
    {
        $this->productFacade->activateProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete): void
    {
        $this->productFacade->deactivateProductConcrete($idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function generateVariants(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection): array
    {
        return $this->productFacade->generateVariants($productAbstractTransfer, $attributeCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedAbstractAttributeKeys(
        ProductAbstractTransfer $productAbstractTransfer,
        ?LocaleTransfer $localeTransfer = null
    ): array {
        return $this->productFacade->getCombinedAbstractAttributeKeys($productAbstractTransfer, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedConcreteAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        ?LocaleTransfer $localeTransfer = null
    ): array {
        return $this->productFacade->getCombinedConcreteAttributes($productConcreteTransfer, $localeTransfer);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstract($idProductAbstract): void
    {
        $this->productFacade->touchProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcrete($idProductConcrete): void
    {
        $this->productFacade->touchProductConcrete($idProductConcrete);
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku): ?int
    {
        return $this->productFacade->findProductConcreteIdBySku($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function generateProductConcreteSku(
        ProductAbstractTransfer $productAbstractTransfer,
        ProductConcreteTransfer $productConcreteTransfer
    ): string {
        return $this->productFacade->generateProductConcreteSku($productAbstractTransfer, $productConcreteTransfer);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, string>
     */
    public function getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract(array $productAbstractIds): array
    {
        return $this->productFacade->getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract($productAbstractIds);
    }
}
