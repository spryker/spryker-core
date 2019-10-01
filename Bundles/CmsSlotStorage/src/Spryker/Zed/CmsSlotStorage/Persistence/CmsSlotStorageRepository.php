<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStoragePersistenceFactory getFactory()
 */
class CmsSlotStorageRepository extends AbstractRepository implements CmsSlotStorageRepositoryInterface
{
    /**
     * @param string[] $cmsSlotKeys
     *
     * @return \Generated\Shared\Transfer\SpyCmsSlotStorageEntityTransfer[]
     */
    public function getCmsStorageStorageEntitiesByCmsSlotKeys(array $cmsSlotKeys): array
    {
        if (!$cmsSlotKeys) {
            return [];
        }

        $cmsSlotStorageQuery = $this->getFactory()
            ->getCmsSlotStorageQuery()
            ->filterByCmsSlotKey_In($cmsSlotKeys);

        return $this->buildQueryFromCriteria($cmsSlotStorageQuery)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $cmsSlotIds
     *
     * @return \Generated\Shared\Transfer\SpyCmsSlotStorageEntityTransfer[]
     */
    public function getFilteredCmsSlotStorageEntities(FilterTransfer $filterTransfer, array $cmsSlotIds): array
    {
        $cmsSlotStorageQuery = $this->getFactory()
            ->getCmsSlotStorageQuery();

        if ($cmsSlotIds) {
            $cmsSlotStorageQuery->filterByIdCmsSlotStorage_In($cmsSlotIds);
        }

        return $this->buildQueryFromCriteria($cmsSlotStorageQuery, $filterTransfer)->find();
    }
}
