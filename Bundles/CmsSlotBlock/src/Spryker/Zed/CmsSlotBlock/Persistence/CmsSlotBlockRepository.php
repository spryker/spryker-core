<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockPersistenceFactory getFactory()
 */
class CmsSlotBlockRepository extends AbstractRepository implements CmsSlotBlockRepositoryInterface
{
    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlocks(int $idCmsSlotTemplate, int $idCmsSlot): CmsSlotBlockCollectionTransfer
    {
        $cmsSlotBlockEntities = $this->getFactory()
            ->getCmsSlotBlockQuery()
            ->filterByFkCmsSlotTemplate($idCmsSlotTemplate)
            ->filterByFkCmsSlot($idCmsSlot)
            ->find();

        return $this->getFactory()
            ->createCmsSlotBlockMapper()
            ->mapCmsSlotBlockEntityCollectionToTransferCollection(
                $cmsSlotBlockEntities,
                new CmsSlotBlockCollectionTransfer()
            );
    }

    /**
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksWithSlotRelations(): array
    {
        $cmsBlockEntities = $this->getFactory()
            ->getCmsBlockQuery()
            ->leftJoinWithSpyCmsSlotBlock()
            ->leftJoinWithSpyCmsBlockStore()
            ->useSpyCmsBlockStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyStore()
            ->endUse()
            ->orderBy(SpyCmsBlockTableMap::COL_NAME)
            ->find();

        return $this->getFactory()
            ->createCmsBlockMapper()
            ->mapCmsBlockEntitiesToTransfers($cmsBlockEntities);
    }
}
