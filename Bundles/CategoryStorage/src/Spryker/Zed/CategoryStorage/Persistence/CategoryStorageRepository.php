<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStoragePersistenceFactory getFactory()
 */
class CategoryStorageRepository extends AbstractRepository implements CategoryStorageRepositoryInterface
{
    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function getCategoryNodeStorageTransfersByCategoryNodeIds(array $categoryNodeIds): array
    {
        $categoryNodeStorageEntities = $this->getFactory()
            ->createSpyCategoryNodeStorageQuery()
            ->filterByFkCategoryNode_In($categoryNodeIds)
            ->find();

        if (!$categoryNodeStorageEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createCategoryNodeStorageMapper()
            ->mapCategoryNodeStorageEntitiesToCategoryNodeStorageTransfers(
                $categoryNodeStorageEntities,
                []
            );
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTreeStorageTransfer[]
     */
    public function getCategoryTreeStorageTransfers(): array
    {
        $categoryTreeStorageEntities = $this->getFactory()
            ->createSpyCategoryTreeStorageQuery()
            ->find();

        if ($categoryTreeStorageEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createCategoryTreeStorageMapper()
            ->mapCategoryTreeStorageEntitiesToCategoryTreeStorageTransfers($categoryTreeStorageEntities, []);
    }
}
