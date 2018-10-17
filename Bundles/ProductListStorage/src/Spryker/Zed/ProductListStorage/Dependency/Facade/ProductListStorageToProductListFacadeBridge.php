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
    public function getProductBlacklistIdsIdProductAbstract(int $idProductAbstract): array
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
    public function getProductBlacklistIdsByIdProductConcrete(int $idProductConcrete): array
    {
        return $this->productListFacade->getProductBlacklistIdsByIdProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProductConcrete(int $idProductConcrete): array
    {
        return $this->productListFacade->getProductWhitelistIdsByIdProductConcrete($idProductConcrete);
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
}
