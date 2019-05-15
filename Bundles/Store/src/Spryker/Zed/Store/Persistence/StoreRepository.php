<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Store\Persistence\StorePersistenceFactory getFactory()
 */
class StoreRepository extends AbstractRepository implements StoreRepositoryInterface
{
    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $name): ?StoreTransfer
    {
        $storeEntity = $this->getFactory()
            ->createStoreQuery()
            ->filterByName($name)
            ->findOne();

        if (!$storeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createStoreMapper()
            ->mapStoreTransfer($storeEntity);
    }
}
