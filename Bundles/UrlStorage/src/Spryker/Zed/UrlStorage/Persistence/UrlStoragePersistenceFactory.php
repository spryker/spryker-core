<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Persistence;

use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Orm\Zed\UrlStorage\Persistence\SpyUrlRedirectStorageQuery;
use Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\UrlStorage\UrlStorageDependencyProvider;

/**
 * @method \Spryker\Zed\UrlStorage\UrlStorageConfig getConfig()
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageRepositoryInterface getRepository()
 */
class UrlStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery
     */
    public function createSpyStorageUrlQuery()
    {
        return SpyUrlStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlRedirectStorageQuery
     */
    public function createSpyStorageUrlRedirectQuery()
    {
        return SpyUrlRedirectStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function getUrlQuery(): SpyUrlQuery
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::PROPEL_QUERY_URL);
    }

    /**
     * @return \Spryker\Zed\UrlStorage\Dependency\QueryContainer\UrlStorageToUrlQueryContainerInterface
     */
    public function getUrlQueryContainer()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::QUERY_CONTAINER_URL);
    }
}
