<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface;
use Spryker\Shared\Store\Reader\StoreReader;
use Spryker\Shared\Store\Reader\StoreReaderInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Store\Persistence\Propel\Mapper\StoreMapper;
use Spryker\Zed\Store\StoreDependencyProvider;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Store\Persistence\StoreRepositoryInterface getRepository()
 */
class StorePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function createStoreQuery()
    {
        return SpyStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\Store\Persistence\Propel\Mapper\StoreMapper
     */
    public function createStoreMapper(): StoreMapper
    {
        return new StoreMapper($this->createSharedStoreReader());
    }

    /**
     * @return \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    public function createSharedStoreReader(): StoreReaderInterface
    {
        return new StoreReader($this->getStore());
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    public function getStore(): StoreToStoreInterface
    {
        return $this->getProvidedDependency(StoreDependencyProvider::STORE);
    }
}
