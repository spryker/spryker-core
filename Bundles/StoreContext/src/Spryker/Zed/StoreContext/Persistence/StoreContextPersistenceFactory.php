<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Persistence;

use Orm\Zed\StoreContext\Persistence\SpyStoreContextQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\StoreContext\Dependency\Service\StoreContextToUtilEncodingServiceInterface;
use Spryker\Zed\StoreContext\Persistence\Propel\Mapper\StoreContextMapper;
use Spryker\Zed\StoreContext\StoreContextDependencyProvider;

/**
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextRepositoryInterface getRepository()
 * @method \Spryker\Zed\StoreContext\StoreContextConfig getConfig()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextEntityManagerInterface getEntityManager()
 */
class StoreContextPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\StoreContext\Persistence\SpyStoreContextQuery
     */
    public function createStoreContextQuery(): SpyStoreContextQuery
    {
        return SpyStoreContextQuery::create();
    }

    /**
     * @return \Spryker\Zed\StoreContext\Persistence\Propel\Mapper\StoreContextMapper
     */
    public function createStoreContextMapper(): StoreContextMapper
    {
        return new StoreContextMapper($this->getServiceUtilEncoding());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Dependency\Service\StoreContextToUtilEncodingServiceInterface
     */
    public function getServiceUtilEncoding(): StoreContextToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(StoreContextDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
