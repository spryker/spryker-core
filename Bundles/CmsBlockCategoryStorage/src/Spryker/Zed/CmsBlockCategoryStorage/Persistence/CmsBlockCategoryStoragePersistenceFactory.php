<?php

namespace Spryker\Zed\CmsBlockCategoryStorage\Persistence;

use Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorageQuery;
use Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageDependencyProvider;
use Spryker\Zed\CmsBlockCategoryStorage\Dependency\QueryContainer\CmsBlockCategoryStorageToCmsBlockCategoryConnectorQueryContainerInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainer getQueryContainer()
 */
class CmsBlockCategoryStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return SpyCmsBlockCategoryStorageQuery
     */
    public function createSpyCmsBlockCategoryStorageQuery()
    {
        return SpyCmsBlockCategoryStorageQuery::create();
    }

    /**
     * @return CmsBlockCategoryStorageToCmsBlockCategoryConnectorQueryContainerInterface
     */
    public function getCmsBlockCategoryConnectorQuery()
    {
        return $this->getProvidedDependency(CmsBlockCategoryStorageDependencyProvider::QUERY_CONTAINER_CMS_BLOCK_CATEGORY_CONNECTOR);
    }
}
