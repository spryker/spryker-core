<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Persistence;

use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery;
use Spryker\Zed\CategoryPageSearch\CategoryPageSearchDependencyProvider;
use Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface;
use Spryker\Zed\CategoryPageSearch\Persistence\Propel\Mapper\CategoryNodePageSearchMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CategoryPageSearch\CategoryPageSearchConfig getConfig()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchRepositoryInterface getRepository()
 */
class CategoryPageSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery
     */
    public function createSpyCategoryNodePageSearchQuery()
    {
        return SpyCategoryNodePageSearchQuery::create();
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Persistence\Propel\Mapper\CategoryNodePageSearchMapper
     */
    public function createCategoryNodePageSearchMapper(): CategoryNodePageSearchMapper
    {
        return new CategoryNodePageSearchMapper($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\QueryContainer\CategoryPageSearchToCategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface
     */
    public function getUtilEncodingService(): CategoryPageSearchToUtilEncodingInterface
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
