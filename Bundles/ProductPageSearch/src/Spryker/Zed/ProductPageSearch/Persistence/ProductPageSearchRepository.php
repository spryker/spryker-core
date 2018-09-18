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
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function findProductConcretePageSearchTransfers(array $productConcreteIds = []): array
    {
        $productConcretePageSearchTransfers = [];
        $mapper = $this->getFactory()->createProductPageSearchMapper();
        $query = $this->getFactory()->createProductConcretePageSearchQuery();

        if (!empty($productConcreteIds)) {
            $query->filterByFkProduct_In($productConcreteIds);
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
