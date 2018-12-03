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
     * @param array $categoryImageSetToCategoryImageIds
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryIdsByCategoryImageSetToCategoryImageIds(array $categoryImageSetToCategoryImageIds);

    /**
     * @param array $categoryIds
     *
     * @return \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[]
     */
    public function findCategoryImageSetsByFkCategoryIn(array $categoryIds): array;

    /**
     * @param array $categoryIds
     *
     * @return \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer[]
     */
    public function findCategoryImageStorageByFkCategoryIn(array $categoryIds): array;

    /**
     * @param array $categoryImageIds
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryIdsByCategoryImageIds(array $categoryImageIds);
}
