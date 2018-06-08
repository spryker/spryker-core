<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Model;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface;

class ProductListProductConcreteRelationReader implements ProductListProductConcreteRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface
     */
    protected $productListRepository;

    /**
     * @param \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface $productListRepository
     */
    public function __construct(
        ProductListRepositoryInterface $productListRepository
    ) {
        $this->productListRepository = $productListRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    public function getProductListProductConcreteRelation(
        ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
    ): ProductListProductConcreteRelationTransfer {
        $productListProductConcreteRelationTransfer->requireIdProductList();
        $productIds = $this->productListRepository->getRelatedProductConcreteIdsByIdProductList($productListProductConcreteRelationTransfer->getIdProductList());
        $productListProductConcreteRelationTransfer->setProductIds($productIds);

        return $productListProductConcreteRelationTransfer;
    }
}
