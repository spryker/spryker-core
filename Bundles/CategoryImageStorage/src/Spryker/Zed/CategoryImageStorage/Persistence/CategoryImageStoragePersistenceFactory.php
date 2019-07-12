<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorageQuery;
use Spryker\Zed\CategoryImageStorage\CategoryImageStorageDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface getRepository()
 */
class CategoryImageStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery
     */
    public function getQueryCategoryImageSetToCategoryImage(): SpyCategoryImageSetToCategoryImageQuery
    {
        return $this->getProvidedDependency(CategoryImageStorageDependencyProvider::PROPEL_QUERY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE);
    }

    /**
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery
     */
    public function getCategoryImageSetQuery(): SpyCategoryImageSetQuery
    {
        return $this->getProvidedDependency(CategoryImageStorageDependencyProvider::PROPEL_QUERY_CATEGORY_IMAGE_SET);
    }

    /**
     * @return \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorageQuery
     */
    public function createSpyCategoryImageStorageQuery(): SpyCategoryImageStorageQuery
    {
        return SpyCategoryImageStorageQuery::create();
    }
}
