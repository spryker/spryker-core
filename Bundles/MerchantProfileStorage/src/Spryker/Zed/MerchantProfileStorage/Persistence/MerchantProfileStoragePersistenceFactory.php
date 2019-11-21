<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence;

use Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageEntityManagerInterface getEntityManager()
 */
class MerchantProfileStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorageQuery
     */
    public function createMerchantProfileStorageQuery(): SpyMerchantProfileStorageQuery
    {
        return SpyMerchantProfileStorageQuery::create();
    }
}
