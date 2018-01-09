<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Persistence;

use Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorageQuery;
use Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 */
class CmsBlockCategoryStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorageQuery
     */
    public function createSpyCmsBlockCategoryStorageQuery()
    {
        return SpyCmsBlockCategoryStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryStorage\Dependency\QueryContainer\CmsBlockCategoryStorageToCmsBlockCategoryConnectorQueryContainerInterface
     */
    public function getCmsBlockCategoryConnectorQuery()
    {
        return $this->getProvidedDependency(CmsBlockCategoryStorageDependencyProvider::QUERY_CONTAINER_CMS_BLOCK_CATEGORY_CONNECTOR);
    }
}
