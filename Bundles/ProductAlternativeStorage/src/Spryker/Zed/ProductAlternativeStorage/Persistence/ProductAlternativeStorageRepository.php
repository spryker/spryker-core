<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStoragePersistenceFactory getFactory()
 */
class ProductAlternativeStorageRepository extends AbstractRepository implements ProductAlternativeStorageRepositoryInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer
     */
    public function findProductAlternativeStorageEntity($idProduct): SpyProductAlternativeStorageEntityTransfer
    {
        $query = $this->getFactory()
            ->createProductAlternativeStorageQuery()
            ->filterByFkProductAlternative($idProduct);

        return $this->buildQueryFromCriteria($query)->findOneOrCreate();
    }
}
