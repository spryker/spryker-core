<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockPersistenceFactory getFactory()
 */
class CmsSlotBlockRepository extends AbstractRepository implements CmsSlotBlockRepositoryInterface
{
    /**
     * @param int[] $slotIds
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer[]
     */
    public function getCmsSlotBlocksBySlotIds(array $slotIds): array
    {
        $cmsSLotBlocks = $this->getFactory()
            ->getCmsSLotBlockQuery()
            ->filterByFkCmsSlot_In($slotIds)
            ->find();

        return $this->getFactory()->createCmsSlotBlockMapper()->mapCmsSlotBlockEntitiesToTransfers($cmsSLotBlocks);
    }
}
