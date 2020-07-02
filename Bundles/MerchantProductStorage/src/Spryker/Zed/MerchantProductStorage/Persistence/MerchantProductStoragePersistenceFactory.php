<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Persistence;

use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\MerchantProductStorage\Persistence\SpyMerchantProductAbstractStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantProductStorage\MerchantProductStorageDependencyProvider;
use Spryker\Zed\MerchantProductStorage\Persistence\Propel\Mapper\MerchantProductStorageMapper;

/**
 * @method \Spryker\Zed\MerchantProductStorage\MerchantProductStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageRepositoryInterface getRepository()
 */
class MerchantProductStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantProductStorage\Persistence\SpyMerchantProductAbstractStorageQuery
     */
    public function createMerchantProductStoragePropelQuery(): SpyMerchantProductAbstractStorageQuery
    {
        return new SpyMerchantProductAbstractStorageQuery();
    }

    /**
     * @return \Spryker\Zed\MerchantProductStorage\Persistence\Propel\Mapper\MerchantProductStorageMapper
     */
    public function createMerchantProductStorageMapper(): MerchantProductStorageMapper
    {
        return new MerchantProductStorageMapper();
    }

    /**
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    public function getMerchantProductAbstractPropelQuery(): SpyMerchantProductAbstractQuery
    {
        return $this->getProvidedDependency(MerchantProductStorageDependencyProvider::PROPEL_QUERY_MERCHANT_PRODUCT);
    }
}
