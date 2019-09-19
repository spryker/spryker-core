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
     * {@inheritdoc}
     */
    public function saveCategoryImageStorage(SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransfer)
    {
        $this->save($categoryImageStorageEntityTransfer);
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

        $categoryImageStorageEntity->delete();
    }
}
