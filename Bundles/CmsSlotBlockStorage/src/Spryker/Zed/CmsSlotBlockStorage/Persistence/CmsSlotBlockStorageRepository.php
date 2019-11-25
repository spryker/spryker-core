<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTemplateTableMap;
use Orm\Zed\CmsSlotBlock\Persistence\Base\SpyCmsSlotBlockQuery;
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
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer[]
     */
    public function getCmsSlotBlockStorageTransfersByCmsSlotBlocks(array $cmsSlotBlockTransfers): array
    {
        $cmsSlotBlockQuery = $this->getFactory()
            ->createCmsSlotBlockQuery()
            ->useCmsSlotQuery(null, Criteria::RIGHT_JOIN)
                ->useSpyCmsSlotToCmsSlotTemplateQuery()
                    ->joinWithCmsSlotTemplate()
                ->endUse()
            ->endUse();

        $cmsSlotBlockQuery = $this->addCmsSlotBlockQueryConditionsByCmsSlotBlockTransfers(
            $cmsSlotBlockQuery,
            $cmsSlotBlockTransfers
        );

        $cmsSlotWithSlotTemplateCombinations = $cmsSlotBlockQuery
            ->groupBy([
                SpyCmsSlotTableMap::COL_ID_CMS_SLOT,
                SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE,
            ])
            ->select([
                SpyCmsSlotTableMap::COL_ID_CMS_SLOT,
                SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE,
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
     * @param \Orm\Zed\CmsSlotBlock\Persistence\Base\SpyCmsSlotBlockQuery $cmsSlotBlockQuery
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return \Orm\Zed\CmsSlotBlock\Persistence\Base\SpyCmsSlotBlockQuery
     */
    protected function addCmsSlotBlockQueryConditionsByCmsSlotBlockTransfers(
        SpyCmsSlotBlockQuery $cmsSlotBlockQuery,
        array $cmsSlotBlockTransfers
    ): SpyCmsSlotBlockQuery {
        $criteria = new Criteria();
        $cmsSlotBlockIds = [];

        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            if ($cmsSlotBlockTransfer->getIdSlotTemplate() && $cmsSlotBlockTransfer->getIdSlot()) {
                $slotWithTemplateCriterion = $criteria->getNewCriterion(
                    SpyCmsSlotTableMap::COL_ID_CMS_SLOT,
                    $cmsSlotBlockTransfer->getIdSlot()
                );
                $slotWithTemplateCriterion->addAnd(
                    $criteria->getNewCriterion(
                        SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE,
                        $cmsSlotBlockTransfer->getIdSlotTemplate()
                    )
                );
                $cmsSlotBlockQuery->addOr($slotWithTemplateCriterion);

                continue;
            }

            $cmsSlotBlockIds[] = $cmsSlotBlockTransfer->getIdCmsSlotBlock();
        }

        if ($cmsSlotBlockIds) {
            $slotBlockCriterion = $criteria->getNewCriterion(
                SpyCmsSlotBlockTableMap::COL_ID_CMS_SLOT_BLOCK,
                $cmsSlotBlockIds,
                Criteria::IN
            );
            $cmsSlotBlockQuery->addOr($slotBlockCriterion);
        }

        return $cmsSlotBlockQuery;
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
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollectionByCmsSlotBlockStorageTransfer(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
    ): CmsSlotBlockCollectionTransfer {
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

        $cmsSlotBlockCollectionTransfer = new CmsSlotBlockCollectionTransfer();
        $cmsSlotBlockStorageMapper = $this->getFactory()->createCmsSlotBlockStorageMapper();

        foreach ($cmsSlotBlockEntities as $cmsSlotBlockEntity) {
            $cmsSlotBlockTransfer = $cmsSlotBlockStorageMapper->mapCmsSlotBlockEntityToCmsSlotBlockTransfer(
                $cmsSlotBlockEntity,
                new CmsSlotBlockTransfer()
            );
            $cmsSlotBlockCollectionTransfer->addCmsSlotBlock($cmsSlotBlockTransfer);
        }

        return $cmsSlotBlockCollectionTransfer;
    }
}
