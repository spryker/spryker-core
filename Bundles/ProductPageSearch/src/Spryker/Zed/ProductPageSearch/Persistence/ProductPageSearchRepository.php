<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchPersistenceFactory getFactory()
 */
class ProductPageSearchRepository extends AbstractRepository implements ProductPageSearchRepositoryInterface
{
    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer[]
     */
    public function findProductConcreteSearchPagesByIds(array $ids): array
    {
        $productConcreteSearchPageTransfers = [];
        $mapper = $this->getFactory()->createProductPageSearchMapper();

        $query = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->filterByFkProduct_In($ids);

        $productConcreteSearchPageEntities = $query->find();

        foreach ($productConcreteSearchPageEntities as $productConcreteSearchPageEntity) {
            $productConcreteSearchPageTransfers[] = $mapper->mapProductConcretePageSearchEntityToTransfer(
                $productConcreteSearchPageEntity,
                new ProductPageSearchTransfer()
            );
        }

        return $productConcreteSearchPageTransfers;
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
