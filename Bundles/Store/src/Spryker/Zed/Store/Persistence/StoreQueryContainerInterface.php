<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

/**
 * @method \Spryker\Zed\Store\Persistence\StorePersistenceFactory getFactory()
 */
interface StoreQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $storeName
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoreByName($storeName);

    /**
     * @api
     *
     * @param array $stores
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoresByNames(array $stores);

    /**
     * @api
     *
     * @param int $idStore
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoreById($idStore);
}
