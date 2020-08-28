<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence;

use Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantStorage\Persistence\Mapper\MerchantStorageMapper;

/**
 * @method \Spryker\Zed\MerchantStorage\MerchantStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface getRepository()
 */
class MerchantStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorageQuery
     */
    public function createMerchantStorageQuery(): SpyMerchantStorageQuery
    {
        return SpyMerchantStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantStorage\Persistence\Mapper\MerchantStorageMapper
     */
    public function createMerchantStorageMapper(): MerchantStorageMapper
    {
        return new MerchantStorageMapper();
    }
}
