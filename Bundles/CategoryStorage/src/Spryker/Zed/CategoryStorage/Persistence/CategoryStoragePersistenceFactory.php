<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery;
use Spryker\Zed\CategoryStorage\CategoryStorageDependencyProvider;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CategoryStorage\Persistence\Propel\Mapper\CategoryNodeStorageMapper;
use Spryker\Zed\CategoryStorage\Persistence\Propel\Mapper\CategoryTreeStorageMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface getEntityManager()
 */
class CategoryStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery
     */
    public function createSpyCategoryTreeStorageQuery()
    {
        return SpyCategoryTreeStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery
     */
    public function createSpyCategoryNodeStorageQuery()
    {
        return SpyCategoryNodeStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Persistence\Propel\Mapper\CategoryNodeStorageMapper
     */
    public function createCategoryNodeStorageMapper(): CategoryNodeStorageMapper
    {
        return new CategoryNodeStorageMapper($this->getUtilSanitizeService());
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Persistence\Propel\Mapper\CategoryTreeStorageMapper
     */
    public function createCategoryTreeStorageMapper(): CategoryTreeStorageMapper
    {
        return new CategoryTreeStorageMapper($this->getUtilSanitizeService());
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\QueryContainer\CategoryStorageToCategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService(): CategoryStorageToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
