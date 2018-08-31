<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
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
    public function findProductConcretePageSearchEntities(array $ids = []): array
    {
        $productConcretePageSearchTransfers = [];
        $mapper = $this->getFactory()->createProductPageSearchMapper();
        $query = $this->getFactory()->createProductConcretePageSearchQuery();

        if (!empty($ids)) {
            $query->filterByFkProduct_In($ids);
        }

        $productConcretePageSearchEntities = $query->find();

        foreach ($productConcretePageSearchEntities as $productConcretePageSearchEntity) {
            $productConcretePageSearchTransfers[] = $mapper->mapProductConcretePageSearchEntityToTransfer(
                $productConcretePageSearchEntity,
                new ProductConcretePageSearchTransfer()
            );
        }

        return $productConcretePageSearchTransfers;
    }
}
