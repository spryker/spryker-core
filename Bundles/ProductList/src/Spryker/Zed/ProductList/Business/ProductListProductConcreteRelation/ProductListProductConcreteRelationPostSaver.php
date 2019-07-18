<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface;

class ProductListProductConcreteRelationPostSaver implements ProductListPostSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationWriterInterface
     */
    protected $productListProductConcreteRelationWriter;

    /**
     * @param \Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationWriterInterface $productListProductConcreteRelationWriter
     */
    public function __construct(ProductListProductConcreteRelationWriterInterface $productListProductConcreteRelationWriter)
    {
        $this->productListProductConcreteRelationWriter = $productListProductConcreteRelationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function postSave(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListProductConcreteRelationTransfer = $productListTransfer->getProductListProductConcreteRelation();

        if ($productListProductConcreteRelationTransfer) {
            $productListTransfer = $this->saveProductListProductConcreteRelation(
                $productListTransfer,
                $productListTransfer->getProductListProductConcreteRelation()
            );
        }

        return $productListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function saveProductListProductConcreteRelation(
        ProductListTransfer $productListTransfer,
        ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
    ): ProductListTransfer {
        $productListProductConcreteRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
        $productListProductConcreteRelationTransfer = $this->productListProductConcreteRelationWriter->saveProductListProductConcreteRelation($productListProductConcreteRelationTransfer);

        return $productListTransfer->setProductListProductConcreteRelation($productListProductConcreteRelationTransfer);
    }
}
