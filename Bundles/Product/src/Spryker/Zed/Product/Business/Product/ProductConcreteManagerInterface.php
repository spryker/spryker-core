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
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku);

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Exception
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
    public function findProductConcreteById($idProduct);

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteBySku(string $sku): ?ProductConcreteTransfer;

    /**
     * @param string $localizedName
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteByLocalizedName(string $localizedName): ?ProductConcreteTransfer;

    /**
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct|null
     */
    public function findProductEntityByAbstractAndConcrete(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productConcreteTransfer);
}
