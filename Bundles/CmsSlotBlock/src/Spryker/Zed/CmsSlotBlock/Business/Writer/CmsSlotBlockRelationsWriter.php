<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Writer;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface;

class CmsSlotBlockRelationsWriter implements CmsSlotBlockRelationsWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface
     */
    protected $cmsSlotBlockRepository;

    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface
     */
    protected $cmsSlotBlockEntityManager;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface $cmsSlotBlockRepository
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager
     */
    public function __construct(
        CmsSlotBlockRepositoryInterface $cmsSlotBlockRepository,
        CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager
    ) {
        $this->cmsSlotBlockRepository = $cmsSlotBlockRepository;
        $this->cmsSlotBlockEntityManager = $cmsSlotBlockEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function writeCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void
    {
        $mappedCmsSlotBlockTransfers = $this->getMappedCmsSlotBlockTransfersBySlotKeys($cmsSlotBlockCollectionTransfer);

        foreach ($mappedCmsSlotBlockTransfers as $idCmsSlot => $cmsSlotBlockTransfers) {
            $this->writeCmsSlotBlockRelationsForCmsSlot($cmsSlotBlockTransfers, $idCmsSlot);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     * @param int $idCmsSlot
     *
     * @return void
     */
    protected function writeCmsSlotBlockRelationsForCmsSlot(array $cmsSlotBlockTransfers, int $idCmsSlot): void
    {

    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer[][]
     */
    protected function getMappedCmsSlotBlockTransfersBySlotKeys(
        CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
    ): array {
        $cmsSlotBlockTransfers = $cmsSlotBlockCollectionTransfer->getCmsSlotBlocks();

        $mappedCmsSlotBlockTransfers = [];
        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $mappedCmsSlotBlockTransfers[$cmsSlotBlockTransfer->getIdSlot()][] = $cmsSlotBlockTransfer;
        }

        return $mappedCmsSlotBlockTransfers;
    }
}
