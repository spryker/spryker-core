<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Generated\Shared\Transfer\CategoryImageStorageItemTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStoragePersistenceFactory getFactory()
 */
class CategoryImageStorageEntityManager extends AbstractEntityManager implements CategoryImageStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer
     *
     * @return void
     */
    public function saveCategoryImageStorage(CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer)
    {
        $categoryImageStorageEntity = $this->getFactory()
            ->createSpyCategoryImageStorageQuery()
            ->filterByIdCategoryImageStorage(
                $categoryImageStorageItemTransfer->getIdCategoryImageStorage()
            )
            ->findOneOrCreate();

        $categoryImageStorageEntity = $this->getFactory()
            ->createCategoryImageStorageMapper()
            ->mapCategoryImageStorageItemTransferToCategoryImageStorageEntity(
                $categoryImageStorageItemTransfer,
                $categoryImageStorageEntity
            );

        $categoryImageStorageEntity->save();
    }

    /**
     * @param int $idCategoryImageStorage
     *
     * @return void
     */
    public function deleteCategoryImageStorage(int $idCategoryImageStorage)
    {
        $categoryImageStorageEntity = $this->getFactory()
            ->createSpyCategoryImageStorageQuery()
            ->filterByIdCategoryImageStorage($idCategoryImageStorage)
            ->findOne();

        $categoryImageStorageEntity->delete();
    }
}
