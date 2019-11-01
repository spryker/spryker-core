<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Persistence;

use Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
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
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function getCmsSlotsByCriteriaFilter(CmsSlotCriteriaFilterTransfer $cmsSlotCriteriaFilterTransfer): array
    {
        $cmsSlotQuery = $this->setQueryFilters(
            $this->getFactory()->createCmsSlotQuery(),
            $cmsSlotCriteriaFilterTransfer->getFilter()
        );

        if ($cmsSlotCriteriaFilterTransfer->getCmsSlotIds()) {
            $cmsSlotQuery->filterByIdCmsSlot_In($cmsSlotCriteriaFilterTransfer->getCmsSlotIds());
        }

        $cmsSlotEntities = $cmsSlotQuery->find();

        return $this->getFactory()
            ->createCmsSlotMapper()
            ->mapCmsSlotEntityCollectionToTransferCollection($cmsSlotEntities);
    }

    /**
     * @param \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery $cmsSlotQuery
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery
     */
    protected function setQueryFilters(
        SpyCmsSlotQuery $cmsSlotQuery,
        ?FilterTransfer $filterTransfer
    ): SpyCmsSlotQuery {
        if (!$filterTransfer) {
            return $cmsSlotQuery;
        }

        if ($filterTransfer->getLimit()) {
            $cmsSlotQuery->setLimit($filterTransfer->getLimit());
        }

        if ($filterTransfer->getOffset()) {
            $cmsSlotQuery->setOffset($filterTransfer->getOffset());
        }

        if ($filterTransfer->getOrderBy()) {
            $cmsSlotQuery->orderBy($filterTransfer->getOrderBy());
        }

        return $cmsSlotQuery;
    }
}
