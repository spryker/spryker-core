<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Store\Persistence\StorePersistenceFactory getFactory()
 */
class StoreQueryContainer extends AbstractQueryContainer implements StoreQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $storeName
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoreByName($storeName)
    {
        return $this->getFactory()
            ->createStoreQuery()
            ->filterByName($storeName);
    }

    /**
     * @api
     *
     * @param array $stores
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoresByNames(array $stores)
    {
        return $this->getFactory()
            ->createStoreQuery()
            ->filterByName($stores, Criteria::IN);
    }

    /**
     * @api
     *
     * @param int $idStore
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoreById($idStore)
    {
        return $this->getFactory()
            ->createStoreQuery()
            ->filterByIdStore($idStore);
    }
}
