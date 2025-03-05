<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Persistence;

use Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorageQuery;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\CmsStorage\CmsStorageDependencyProvider;
use Spryker\Zed\CmsStorage\Persistence\Propel\Mapper\CmsStorageMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsStorage\CmsStorageConfig getConfig()
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageRepositoryInterface getRepository()
 */
class CmsStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorageQuery
     */
    public function createSpyCmsStorageQuery()
    {
        return SpyCmsPageStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getLocalePropelQuery(): SpyLocaleQuery
    {
        return $this->getProvidedDependency(CmsStorageDependencyProvider::PROPEL_QUERY_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsStorage\Dependency\QueryContainer\CmsStorageToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(CmsStorageDependencyProvider::QUERY_CONTAINER_CMS_PAGE);
    }

    /**
     * @return \Spryker\Zed\CmsStorage\Persistence\Propel\Mapper\CmsStorageMapper
     */
    public function createCmsStorageMapper(): CmsStorageMapper
    {
        return new CmsStorageMapper();
    }
}
