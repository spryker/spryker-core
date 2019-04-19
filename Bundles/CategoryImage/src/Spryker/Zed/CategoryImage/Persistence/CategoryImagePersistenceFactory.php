<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Spryker\Zed\CategoryImage\CategoryImageDependencyProvider;
use Spryker\Zed\CategoryImage\Persistence\Propel\Mapper\CategoryImageMapper;
use Spryker\Zed\CategoryImage\Persistence\Propel\Mapper\CategoryImageMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CategoryImage\CategoryImageConfig getConfig()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface getRepository()
 */
class CategoryImagePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CategoryImage\Persistence\Propel\Mapper\CategoryImageMapperInterface
     */
    public function createCategoryImageMapper(): CategoryImageMapperInterface
    {
        return new CategoryImageMapper(
            $this->getProvidedDependency(CategoryImageDependencyProvider::FACADE_LOCALE)
        );
    }

    /**
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery
     */
    public function getCategoryImageQuery(): SpyCategoryImageQuery
    {
        return $this->getProvidedDependency(CategoryImageDependencyProvider::PROPEL_QUERY_CATEGORY_IMAGE);
    }

    /**
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery
     */
    public function getCategoryImageSetQuery(): SpyCategoryImageSetQuery
    {
        return $this->getProvidedDependency(CategoryImageDependencyProvider::PROPEL_QUERY_CATEGORY_IMAGE_SET);
    }

    /**
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery
     */
    public function getCategoryImageSetToCategoryImageQuery(): SpyCategoryImageSetToCategoryImageQuery
    {
        return $this->getProvidedDependency(CategoryImageDependencyProvider::PROPEL_QUERY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE);
    }
}
