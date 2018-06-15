<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Model;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface;

class ProductListReader implements ProductListReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationReaderInterface
     */
    protected $productListCategoryRelationReader;

    /**
     * @var \Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationReaderInterface
     */
    private $productListProductConcreteRelationReader;

    /**
     * @param \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface $repository
     * @param \Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationReaderInterface $productListCategoryRelationReader
     * @param \Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationReaderInterface $productListProductConcreteRelationReader
     */
    public function __construct(
        ProductListRepositoryInterface $repository,
        ProductListCategoryRelationReaderInterface $productListCategoryRelationReader,
        ProductListProductConcreteRelationReaderInterface $productListProductConcreteRelationReader
    ) {
        $this->repository = $repository;
        $this->productListCategoryRelationReader = $productListCategoryRelationReader;
        $this->productListProductConcreteRelationReader = $productListProductConcreteRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function findProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListTransfer->requireIdProductList();

        $productListTransfer = $this->repository
            ->getProductListById($productListTransfer->getIdProductList());

        $productListCategoryRelationTransfer = new ProductListCategoryRelationTransfer();
        $productListCategoryRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
        $productListCategoryRelationTransfer = $this->productListCategoryRelationReader
            ->getProductListCategoryRelation($productListCategoryRelationTransfer);
        $productListTransfer->setProductListCategoryRelation($productListCategoryRelationTransfer);

        $productListProductConcreteRelationTransfer = new ProductListProductConcreteRelationTransfer();
        $productListProductConcreteRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
        $productListProductConcreteRelationTransfer = $this->productListProductConcreteRelationReader
            ->getProductListProductConcreteRelation($productListProductConcreteRelationTransfer);
        $productListTransfer->setProductListProductConcreteRelation($productListProductConcreteRelationTransfer);

        return $productListTransfer;
    }
}
