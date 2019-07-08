<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Dependency\Facade;

class ProductListSearchToProductListFacadeBridge implements ProductListSearchToProductListFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param \Spryker\Zed\ProductList\Business\ProductListFacadeInterface $productListFacade
     */
    public function __construct($productListFacade)
    {
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListFacade->getProductBlacklistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListFacade->getProductWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array
    {
        return $this->productListFacade->getProductAbstractIdsByProductListIds($productListIds);
    }

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductListIds(array $productListIds): array
    {
        return $this->productListFacade->getProductConcreteIdsByProductListIds($productListIds);
    }

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProduct(int $idProduct): array
    {
        return $this->productListFacade
            ->getProductWhitelistIdsByIdProduct($idProduct);
    }

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProduct(int $idProduct): array
    {
        return $this->productListFacade
            ->getProductBlacklistIdsByIdProduct($idProduct);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractListIdsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->productListFacade->getProductAbstractListIdsByProductAbstractIds($productAbstractIds);
    }
}
