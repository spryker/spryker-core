<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\Merchant\Persistence\SpyMerchantStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantMapper;
use Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantMapperInterface;
use Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantStoreMapper;
use Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantStoreMapperInterface;

/**
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface getRepository()
 */
class MerchantPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    public function createMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }

    /**
     * @return \Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantMapperInterface
     */
    public function createPropelMerchantMapper(): MerchantMapperInterface
    {
        return new MerchantMapper($this->createMerchantStoreMapper());
    }

    /**
     * @return \Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantStoreMapperInterface
     */
    public function createMerchantStoreMapper(): MerchantStoreMapperInterface
    {
        return new MerchantStoreMapper();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantStoreQuery
     */
    public function createMerchantStoreQuery(): SpyMerchantStoreQuery
    {
        return SpyMerchantStoreQuery::create();
    }
}
