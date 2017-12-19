<?php

namespace Spryker\Zed\CmsBlockProductStorage\Persistence;

use Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery;
use Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageDependencyProvider;
use Spryker\Zed\CmsBlockProductStorage\Dependency\QueryContainer\CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainer getQueryContainer()
 */
class CmsBlockProductStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return SpyCmsBlockProductStorageQuery
     */
    public function createSpyCmsBlockProductStorageQuery()
    {
        return SpyCmsBlockProductStorageQuery::create();
    }

    /**
     * @return CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerInterface
     */
    public function getCmsBlockProductConnectorQuery()
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::QUERY_CONTAINER_CMS_BLOCK_PRODUCT_CONNECTOR);
    }
}
