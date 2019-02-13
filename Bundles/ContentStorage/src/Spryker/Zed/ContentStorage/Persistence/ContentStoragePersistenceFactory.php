<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence;

use Orm\Zed\Content\Persistence\SpyContentQuery;
use Orm\Zed\ContentStorage\Persistence\SpyContentStorageQuery;
use Spryker\Zed\ContentStorage\ContentStorageDependencyProvider;
use Spryker\Zed\ContentStorage\Persistence\Propel\Mapper\ContentStorageMapper;
use Spryker\Zed\ContentStorage\Persistence\Propel\Mapper\ContentStorageMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface getRepository()
 */
class ContentStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ContentStorage\Persistence\SpyContentStorageQuery
     */
    public function createContentStorageQuery(): SpyContentStorageQuery
    {
        return SpyContentStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ContentStorage\Persistence\Propel\Mapper\ContentStorageMapperInterface
     */
    public function createContentStorageMapper(): ContentStorageMapperInterface
    {
        return new ContentStorageMapper();
    }

    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    public function getContentQuery(): SpyContentQuery
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::PROPEL_QUERY_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentStorage\Dependency\Service\ContentStorageToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
