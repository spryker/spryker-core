<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Persistence;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsSlot\Persistence\CmsSlotPersistenceFactory getFactory()
 */
class CmsSlotRepository extends AbstractRepository implements CmsSlotRepositoryInterface
{
    /**
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer|null
     */
    public function findCmsSlotById(int $idCmsSlot): ?CmsSlotTransfer
    {
        $cmsSlot = $this->getFactory()->createCmsSlotQuery()->findOneByIdCmsSlot($idCmsSlot);

        if (!$cmsSlot) {
            return null;
        }

        return $this->getFactory()->createCmsSlotMapper()->mapCmsSlotEntityToTransfer($cmsSlot);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getFilteredCmsSlots(FilterTransfer $filterTransfer): array
    {
        $cmsSlotEntities = $this->buildQueryFromCriteria(
            $this->getFactory()->createCmsSlotQuery(),
            $filterTransfer
        )->find();

        return $this->getFactory()
            ->createCmsSlotMapper()
            ->mapCmsSlotEntityCollectionToTransferCollection($cmsSlotEntities);
    }

    /**
     * @param int[] $cmsSlotIds
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getCmsSlotsByCmsSlotIds(array $cmsSlotIds): array
    {
        if (!$cmsSlotIds) {
            return [];
        }

        $cmsSlotEntities = $this->buildQueryFromCriteria(
            $this->getFactory()->createCmsSlotQuery()->filterByIdCmsSlot_In($cmsSlotIds)
        )->find();

        return $this->getFactory()
            ->createCmsSlotMapper()
            ->mapCmsSlotEntityCollectionToTransferCollection($cmsSlotEntities);
    }
}
