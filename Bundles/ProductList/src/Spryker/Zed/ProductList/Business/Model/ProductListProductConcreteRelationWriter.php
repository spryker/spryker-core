<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Model;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface;

class ProductListProductConcreteRelationWriter implements ProductListProductConcreteRelationWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface
     */
    protected $productListEntityManager;

    /**
     * @var \Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationReaderInterface
     */
    protected $productListProductConcreteRelationReader;

    /**
     * @param \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface $productListEntityManager
     * @param \Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationReaderInterface $productListProductConcreteRelationReader
     */
    public function __construct(
        ProductListEntityManagerInterface $productListEntityManager,
        ProductListProductConcreteRelationReaderInterface $productListProductConcreteRelationReader
    ) {
        $this->productListEntityManager = $productListEntityManager;
        $this->productListProductConcreteRelationReader = $productListProductConcreteRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
     *
     * @return void
     */
    public function saveProductListProductConcreteRelation(
        ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
    ): void {
        $productListProductConcreteRelationTransfer->requireIdProductList();
        $idProductList = $productListProductConcreteRelationTransfer->getIdProductList();

        $requestedProductConcreteIds = $this->getRequestedProductConcreteIds($productListProductConcreteRelationTransfer);
        $currentProductConcreteIds = $this->getRelatedProductConcreteIds($productListProductConcreteRelationTransfer);

        $saveProductConcreteIds = array_diff($requestedProductConcreteIds, $currentProductConcreteIds);
        $deleteProductConcreteIds = array_diff($currentProductConcreteIds, $requestedProductConcreteIds);

        $this->productListEntityManager->addProductConcreteRelations($idProductList, $saveProductConcreteIds);
        $this->productListEntityManager->removeProductConcreteRelations($idProductList, $deleteProductConcreteIds);

        $productListProductConcreteRelationTransfer->setProductIds(
            $this->getRelatedProductConcreteIds($productListProductConcreteRelationTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
     *
     * @return array
     */
    protected function getRelatedProductConcreteIds(
        ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
    ): array {
        $currentProductListProductConcreteRelationTransfer = $this->productListProductConcreteRelationReader->getProductListProductConcreteRelation($productListProductConcreteRelationTransfer);

        if (!$currentProductListProductConcreteRelationTransfer->getProductIds()) {
            return [];
        }

        return $currentProductListProductConcreteRelationTransfer->getProductIds();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
     *
     * @return array
     */
    protected function getRequestedProductConcreteIds(
        ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
    ): array {
        if (!$productListProductConcreteRelationTransfer->getProductIds()) {
            return [];
        }

        return $productListProductConcreteRelationTransfer->getProductIds();
    }
}
