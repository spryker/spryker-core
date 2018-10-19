<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStoragePersistenceFactory getFactory()
 */
interface CategoryImageStorageRepositoryInterface
{
    /**
     * @api
     *
     * @param array $categoryImageSetToCategoryImageIds
     *
     * @return mixed|\Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryIdsByCategoryImageSetToCategoryImageIds(array $categoryImageSetToCategoryImageIds);

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryAttributesByIds(array $categoryIds);

    /**
     * @api
     *
     * @param array $categoryFks
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryImageSetsByFkCategoryIn(array $categoryFks);

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryImageStorageByIds(array $categoryIds);

    /**
     * @param array $categoryImageIds
     *
     * @return mixed|\Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryIdsByCategoryImageIds(array $categoryImageIds);
}
