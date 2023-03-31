<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

/**
 * @deprecated Will be removed without replacement.
 *
 * @method \Spryker\Zed\Store\Persistence\StorePersistenceFactory getFactory()
 */
interface StoreQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoreByName($storeName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array<string> $stores
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoresByNames(array $stores);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idStore
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function queryStoreById($idStore);
}
