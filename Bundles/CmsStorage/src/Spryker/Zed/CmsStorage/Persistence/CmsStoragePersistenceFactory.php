<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Persistence;

use Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorageQuery;
use Spryker\Zed\CmsStorage\CmsStorageDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsStorage\CmsStorageConfig getConfig()
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface getQueryContainer()
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
     * @return \Spryker\Zed\CmsStorage\Dependency\QueryContainer\CmsStorageToLocaleQueryContainerInterface
     */
    public function getLocaleQueryContainer()
    {
        return $this->getProvidedDependency(CmsStorageDependencyProvider::QUERY_CONTAINER_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsStorage\Dependency\QueryContainer\CmsStorageToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(CmsStorageDependencyProvider::QUERY_CONTAINER_CMS_PAGE);
    }
}
