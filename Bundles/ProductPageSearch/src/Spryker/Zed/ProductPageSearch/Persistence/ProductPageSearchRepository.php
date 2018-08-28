<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchPersistenceFactory getFactory()
 */
class ProductPageSearchRepository extends AbstractRepository implements ProductPageSearchRepositoryInterface
{
    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function findProductConcretePageSearchByProductConcreteIds(array $ids): array
    {
        $productConcretePageSearchTransfers = [];
        $mapper = $this->getFactory()->createProductPageSearchMapper();

        $query = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->filterByFkProduct_In($ids);

        $productConcretePageSearchEntities = $query->find();

        foreach ($productConcretePageSearchEntities as $productConcretePageSearchEntity) {
            $productConcretePageSearchTransfers[$productConcretePageSearchEntity->getFkProduct()][$productConcretePageSearchEntity->getStore()][$productConcretePageSearchEntity->getLocale()] = $mapper->mapProductConcretePageSearchEntityToTransfer(
                $productConcretePageSearchEntity,
                new ProductConcretePageSearchTransfer()
            );
        }

        return $productConcretePageSearchTransfers;
    }

    /**
     * @param array $ids
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function findConcreteProductsByIds(array $ids): array
    {
        $productConcreteTransfers = [];
        $mapper = $this->getFactory()->createProductPageSearchMapper();

        $query = $this->getFactory()
            ->createProductQuery()
            ->joinWithSpyProductAbstract()
            ->joinWithSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->joinWithLocale()
            ->endUse()
            ->useSpyProductAbstractQuery()
                ->joinWithSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinWithSpyStore()
                ->endUse()
            ->endUse()
            ->filterByIdProduct_In($ids);

        $productConcreteEntities = $query->find();

        foreach ($productConcreteEntities as $productConcreteEntity) {
            $productConcreteTransfers[] = $mapper->mapProductConcreteEntityToTransfer(
                $productConcreteEntity,
                new ProductConcreteTransfer()
            );
        }

        return $productConcreteTransfers;
    }
}
