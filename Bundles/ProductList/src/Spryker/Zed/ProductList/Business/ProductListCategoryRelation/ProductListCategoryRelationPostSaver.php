<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductListCategoryRelation;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface;

class ProductListCategoryRelationPostSaver implements ProductListPostSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationWriterInterface
     */
    protected $productListCategoryRelationWriter;

    /**
     * @param \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationWriterInterface $productListCategoryRelationWriter
     */
    public function __construct(
        ProductListCategoryRelationWriterInterface $productListCategoryRelationWriter
    ) {
        $this->productListCategoryRelationWriter = $productListCategoryRelationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function postSave(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListCategoryRelationTransfer = $productListTransfer->getProductListCategoryRelation();

        if ($productListCategoryRelationTransfer) {
            $productListTransfer = $this->saveProductListCategoryRelation(
                $productListTransfer,
                $productListTransfer->getProductListCategoryRelation()
            );
        }

        return $productListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function saveProductListCategoryRelation(
        ProductListTransfer $productListTransfer,
        ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
    ): ProductListTransfer {
        $productListCategoryRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
        $productListCategoryRelationTransfer = $this->productListCategoryRelationWriter->saveProductListCategoryRelation($productListCategoryRelationTransfer);

        return $productListTransfer->setProductListCategoryRelation($productListCategoryRelationTransfer);
    }
}
