<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;

class ProductMerchantPortalGuiToProductFacadeBridge implements ProductMerchantPortalGuiToProductFacadeInterface
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer): int
    {
        return $this->productFacade->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer): int
    {
        return $this->productFacade->saveProductConcrete($productConcreteTransfer);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete(int $idProductConcrete): void
    {
        $this->productFacade->activateProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete(int $idProductConcrete): void
    {
        $this->productFacade->deactivateProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idConcrete
     *
     * @return int|null
     */
    public function findProductAbstractIdByConcreteId(int $idConcrete): ?int
    {
        return $this->productFacade->findProductAbstractIdByConcreteId($idConcrete);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract(string $sku): bool
    {
        return $this->productFacade->hasProductAbstract($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete(string $sku): bool
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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById(int $idProductAbstract): ?ProductAbstractTransfer
    {
        return $this->productFacade->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById(int $idProduct): ?ProductConcreteTransfer
    {
        return $this->productFacade->findProductConcreteById($idProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCriteriaTransfer $productCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByCriteria(ProductCriteriaTransfer $productCriteriaTransfer): array
    {
        return $this->productFacade->getProductConcretesByCriteria($productCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return void
     */
    public function createProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        $this->productFacade->createProductConcreteCollection($productConcreteCollectionTransfer);
    }

    /**
     * @param array<string> $productAbstractSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array
    {
        return $this->productFacade->getRawProductAbstractTransfersByAbstractSkus($productAbstractSkus);
    }
}
