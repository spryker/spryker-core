<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStoragePersistenceFactory getFactory()
 */
class CategoryImageStorageEntityManager extends AbstractEntityManager implements CategoryImageStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransfer
     *
     * @return void
     */
    public function saveCategoryImageStorage(SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransfer)
    {
        $categoryImageStorageEntity = $this->getFactory()
            ->createSpyCategoryImageStorageQuery()
            ->filterByIdCategoryImageStorage(
                $categoryImageStorageEntityTransfer->getIdCategoryImageStorage()
            )
            ->findOneOrCreate();

        $categoryImageStorageEntity->fromArray(
            $categoryImageStorageEntityTransfer->toArray()
        );
        $categoryImageStorageEntity->setIsSendingToQueue(
            $this->getFactory()->getConfig()->isSendingToQueue()
        );

        $categoryImageStorageEntity->save();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteCategoryImageStorage(string $idCategoryImageStorageEntityTransfer)
    {
        $categoryImageStorageEntity = $this->getFactory()
            ->createSpyCategoryImageStorageQuery()
            ->filterByIdCategoryImageStorage($idCategoryImageStorageEntityTransfer)
            ->findOne();

        $categoryImageStorageEntity->setIsSendingToQueue(
            $this->getFactory()->getConfig()->isSendingToQueue()
        );

        $categoryImageStorageEntity->delete();
    }
}
