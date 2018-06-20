<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductList;

use Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface;

class ProductListReader implements ProductListReaderInterface
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
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListRepository->getAbstractProductBlacklistIds($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListRepository->getAbstractProductWhitelistIds($idProductAbstract);
    }
}
