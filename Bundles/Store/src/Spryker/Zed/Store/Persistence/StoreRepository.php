<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Store\Persistence\StorePersistenceFactory getFactory()
 */
class StoreRepository extends AbstractRepository implements StoreRepositoryInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function storeExists(string $name): bool
    {
        return $this->getFactory()
            ->createStoreQuery()
            ->filterByName($name)
            ->exists();
    }
}
