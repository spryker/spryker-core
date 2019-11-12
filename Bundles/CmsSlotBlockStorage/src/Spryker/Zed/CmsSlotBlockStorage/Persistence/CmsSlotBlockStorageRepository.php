<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;
use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTemplateTableMap;
use Orm\Zed\CmsSlotBlock\Persistence\Map\SpyCmsSlotBlockTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStoragePersistenceFactory getFactory()
 */
class CmsSlotBlockStorageRepository extends AbstractRepository implements CmsSlotBlockStorageRepositoryInterface
{
    /**
     * @param string[] $cmsSlotBlockIds
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer[]
     */
    public function getCmsSlotBlockStorageTransfersByCmsSlotBlockIds(array $cmsSlotBlockIds): array
    {
        $cmsSlotWithSlotTemplateCombinations = $this->getFactory()
            ->createCmsSlotBlockQuery()
            ->joinWithCmsSlot()
            ->joinWithCmsSlotTemplate()
            ->filterByIdCmsSlotBlock_In($cmsSlotBlockIds)
            ->groupBy([
                SpyCmsSlotBlockTableMap::COL_FK_CMS_SLOT,
                SpyCmsSlotBlockTableMap::COL_FK_CMS_SLOT_TEMPLATE,
            ])
            ->select([
                SpyCmsSlotBlockTableMap::COL_FK_CMS_SLOT,
                SpyCmsSlotBlockTableMap::COL_FK_CMS_SLOT_TEMPLATE,
                SpyCmsSlotTableMap::COL_KEY,
                SpyCmsSlotTemplateTableMap::COL_PATH,
            ])
            ->find()
            ->toArray();

        $cmsSlotBlockStorageTransfers = [];
        $cmsSlotBlockStorageMapper = $this->getFactory()->createCmsSlotBlockStorageMapper();

        foreach ($cmsSlotWithSlotTemplateCombinations as $cmsSlotWithSlotTemplateCombination) {
            $cmsSlotBlockStorageTransfers[] = $cmsSlotBlockStorageMapper
                ->mapCmsSlotWithTemplateCombinationToCmsSlotBlockStorageTransfer($cmsSlotWithSlotTemplateCombination);
        }

        return $cmsSlotBlockStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $cmsSlotBlockStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByCmsSlotBlockStorageIds(
        FilterTransfer $filterTransfer,
        array $cmsSlotBlockStorageIds
    ): array {
        $query = $this->getFactory()->createCmsSlotBlockStorageQuery();

        if ($cmsSlotBlockStorageIds) {
            $query->filterByIdCmsSlotBlockStorage_In($cmsSlotBlockStorageIds);
        }

        $cmsSlotBlockStorageEntityCollection = $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();

        $synchronizationDataTransfers = [];
        $cmsSlotBlockStorageMapper = $this->getFactory()->createCmsSlotBlockStorageMapper();

        foreach ($cmsSlotBlockStorageEntityCollection as $cmsSlotBlockStorageEntity) {
            $synchronizationDataTransfers[] = $cmsSlotBlockStorageMapper
                ->mapCmsSlotBlockStorageEntityToSynchronizationDataTransfer($cmsSlotBlockStorageEntity);
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer
     */
    public function getCmsSlotBlockStorageDataTransferByCmsSlotBlockStorageTransfer(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
    ): CmsSlotBlockStorageDataTransfer {
        $cmsSlotBlockStorageTransfer->requireIdCmsSlot();
        $cmsSlotBlockStorageTransfer->requireIdCmsSlotTemplate();

        $cmsSlotBlockEntities = $this->getFactory()
            ->createCmsSlotBlockQuery()
            ->filterByFkCmsSlot($cmsSlotBlockStorageTransfer->getIdCmsSlot())
            ->filterByFkCmsSlotTemplate($cmsSlotBlockStorageTransfer->getIdCmsSlotTemplate())
            ->orderByPosition(Criteria::ASC)
            ->innerJoinWithCmsBlock()
            ->find()
            ->getData();

        $cmsSlotBlockStorageDataTransfer = new CmsSlotBlockStorageDataTransfer();
        $cmsSlotBlockStorageMapper = $this->getFactory()->createCmsSlotBlockStorageMapper();

        foreach ($cmsSlotBlockEntities as $cmsSlotBlockEntity) {
            $cmsSlotBlockTransfers[] = $cmsSlotBlockStorageMapper
                ->mapCmsSlotBlockEntityToCmsSlotBlockStorageDataTransfer(
                    $cmsSlotBlockEntity,
                    $cmsSlotBlockStorageDataTransfer
                );
        }

        return $cmsSlotBlockStorageDataTransfer;
    }
}
