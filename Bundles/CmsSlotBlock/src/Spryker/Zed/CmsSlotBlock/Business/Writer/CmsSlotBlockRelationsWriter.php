<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Writer;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface;

class CmsSlotBlockRelationsWriter implements CmsSlotBlockRelationsWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface
     */
    protected $cmsSlotBlockEntityManager;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager
     */
    public function __construct(CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager)
    {
        $this->cmsSlotBlockEntityManager = $cmsSlotBlockEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function saveCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void
    {
        $cmsSlotBlockTransfers = $cmsSlotBlockCollectionTransfer->getCmsSlotBlocks()->getArrayCopy();
        $cmsSlotIds = $this->getUniqueCmsSlotIds($cmsSlotBlockTransfers);

        $this->cmsSlotBlockEntityManager->deleteCmsSlotBlocks($cmsSlotIds);
        $this->cmsSlotBlockEntityManager->createCmsSlotBlocks($cmsSlotBlockTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return int[]
     */
    protected function getUniqueCmsSlotIds(array $cmsSlotBlockTransfers): array
    {
        $cmsSlotIds = [];
        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $cmsSlotId = $cmsSlotBlockTransfer->getIdSlot();

            if (!in_array($cmsSlotId, $cmsSlotIds)) {
                $cmsSlotIds[] = $cmsSlotId;
            }
        }

        return $cmsSlotIds;
    }
}
