<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Dependency\Facade;

class ProductListStorageToProductListFacadeBridge implements ProductListStorageToProductListFacadeInterface
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
    public function getProductAbstractBlacklistIdsIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListFacade->getProductAbstractBlacklistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListFacade->getProductAbstractWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListFacade->getCategoryWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductConcrete(int $idProductConcrete): array
    {
        return $this->productListFacade->getProductAbstractBlacklistIdsByIdProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductConcrete(int $idProductConcrete): array
    {
        return $this->productListFacade->getProductAbstractWhitelistIdsByIdProductConcrete($idProductConcrete);
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
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractListsIdsByIdProductAbstractIn(array $productAbstractIds): array
    {
        return $this->productListFacade->getProductAbstractListsIdsByIdProductAbstractIn($productAbstractIds);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductListsIdsByIdProductIn(array $productConcreteIds): array
    {
        return $this->productListFacade->getProductListsIdsByIdProductIn($productConcreteIds);
    }
}
