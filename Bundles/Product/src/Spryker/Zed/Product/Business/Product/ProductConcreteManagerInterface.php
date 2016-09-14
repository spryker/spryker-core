<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Exception
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function getProductConcreteById($idProduct);

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    public function findProductEntityByAbstract(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productConcreteTransfer);

}
