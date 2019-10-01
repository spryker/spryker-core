<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface;

class ProductListProductConcreteRelationFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @module ProductCategory
     *
     * @param \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface $productListFacade
     */
    public function __construct(
        ProductListGuiToProductListFacadeInterface $productListFacade
    ) {
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param int|null $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    public function getData(?int $idProductList = null): ProductListProductConcreteRelationTransfer
    {
        $productListProductConcreteRelationTransfer = new ProductListProductConcreteRelationTransfer();

        if (!$idProductList) {
            return $productListProductConcreteRelationTransfer;
        }

        $productListTransfer = (new ProductListTransfer())->setIdProductList($idProductList);
        $productListCategoryRelation = $this->productListFacade
            ->getProductListById($productListTransfer)
            ->getProductListProductConcreteRelation();

        $productListCategoryRelation->setIdProductList($productListTransfer->getIdProductList());

        return $productListCategoryRelation;
    }
}
