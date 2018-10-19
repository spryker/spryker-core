<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig getConfig()
 */
class CategoryImageStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery
     */
    public function createQueryCategoryImageSetToCategoryImage(): SpyCategoryImageSetToCategoryImageQuery
    {
        return SpyCategoryImageSetToCategoryImageQuery::create();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function createCategoryAttributeQuery(): SpyCategoryAttributeQuery
    {
        return SpyCategoryAttributeQuery::create();
    }

    /**
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery
     */
    public function createCategoryImageSetQuery(): SpyCategoryImageSetQuery
    {
        return SpyCategoryImageSetQuery::create();
    }

    /**
     * @return \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorageQuery
     */
    public function createSpyCategoryImageStorageQuery(): SpyCategoryImageStorageQuery
    {
        return SpyCategoryImageStorageQuery::create();
    }
}
