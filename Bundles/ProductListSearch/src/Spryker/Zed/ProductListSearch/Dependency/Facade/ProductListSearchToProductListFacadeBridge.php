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
     */// TODO: use
    public function getProductAbstractListsIdsByIdProductAbstractIn(array $productAbstractIds): array
    {
        return $this->productListFacade->getProductAbstractListsIdsByIdProductAbstractIn($productAbstractIds);
    }
}
