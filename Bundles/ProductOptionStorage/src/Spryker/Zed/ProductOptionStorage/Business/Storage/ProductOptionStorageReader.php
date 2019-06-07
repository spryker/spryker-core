<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Business\Storage;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface;

class ProductOptionStorageReader implements ProductOptionStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @param \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade
     */
    public function __construct(ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductOptionGroupStatusesByProductAbstractIds(array $productAbstractIds): array
    {
        $productOptionGroupStatuses = $this->productOptionFacade->getProductAbstractOptionGroupStatusesByProductAbstractIds(
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
