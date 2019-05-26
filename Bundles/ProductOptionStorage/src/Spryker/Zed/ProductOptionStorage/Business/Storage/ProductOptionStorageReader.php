<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Business\Storage;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageRepositoryInterface;

class ProductOptionStorageReader implements ProductOptionStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageRepositoryInterface
     */
    protected $productOptionStorageRepository;

    /**
     * @param \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageRepositoryInterface $productOptionStorageRepository
     */
    public function __construct(ProductOptionStorageRepositoryInterface $productOptionStorageRepository)
    {
        $this->productOptionStorageRepository = $productOptionStorageRepository;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductOptionGroupStatusesByProductAbstractIds(array $productAbstractIds): array
    {
        $productOptionGroupStatuses = $this->productOptionStorageRepository->getProductOptionGroupStatusesByProductAbstractIds(
            $productAbstractIds
        );

        return $this->getIndexedProductOptionGroupStatuses($productOptionGroupStatuses);
    }

    /**
     * @param array $productOptionGroupStatuses
     *
     * @return array [[fkProductAbstract => [productOptionGroupName => productOptionGroupStatus]]]
     */
    protected function getIndexedProductOptionGroupStatuses(array $productOptionGroupStatuses): array
    {
        $indexedProductOptionGroupStatuses = [];

        foreach ($productOptionGroupStatuses as $productOptionGroupStatus) {
            $idProductAbstract = $productOptionGroupStatus[SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT];
            $productOptionGroupName = $productOptionGroupStatus[SpyProductOptionGroupTableMap::COL_NAME];

            $indexedProductOptionGroupStatuses[$idProductAbstract][$productOptionGroupName] = $productOptionGroupStatus[SpyProductOptionGroupTableMap::COL_ACTIVE];
        }

        return $indexedProductOptionGroupStatuses;
    }
}
