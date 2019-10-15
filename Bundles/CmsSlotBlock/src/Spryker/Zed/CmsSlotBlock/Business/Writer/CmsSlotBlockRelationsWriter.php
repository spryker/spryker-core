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
        $mappedCmsSlotBlockTransfers = $this->getMappedCmsSlotBlockTransfers(
            $cmsSlotBlockCollectionTransfer->getCmsSlotBlocks()->getArrayCopy()
        );
        $mappedCmsSlotBlockTransfersFromDb = $this->getMappedCmsSlotBlockTransfers(
            $this->cmsSlotBlockRepository->getCmsSlotBlocksBySlotIds(array_keys($mappedCmsSlotBlockTransfers))
        );

        $this->deleteCmsSlotBlockRelations($mappedCmsSlotBlockTransfersFromDb, $mappedCmsSlotBlockTransfers);

        foreach ($mappedCmsSlotBlockTransfers as $idCmsSlot => $cmsSlotBlockTransfers) {
            $cmsSlotBlockTransfersFromDb = $mappedCmsSlotBlockTransfersFromDb[$idCmsSlot] ?? [];

            $this->updateCmsSlotBlockRelations($cmsSlotBlockTransfers, $cmsSlotBlockTransfersFromDb);
            $this->createCmsSlotBlockRelations($cmsSlotBlockTransfers, $cmsSlotBlockTransfersFromDb);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[][] $mappedCmsSlotBlockTransfersFromDb
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[][] $mappedCmsSlotBlockTransfers
     *
     * @return void
     */
    protected function deleteCmsSlotBlockRelations(
        array $mappedCmsSlotBlockTransfersFromDb,
        array $mappedCmsSlotBlockTransfers
    ): void {
        foreach ($mappedCmsSlotBlockTransfersFromDb as $idCmsSlot => $cmsSlotBlockTransfersFromDb) {
            $cmsBlockIds = array_keys(array_diff_key($cmsSlotBlockTransfersFromDb, $mappedCmsSlotBlockTransfers[$idCmsSlot]));

            $this->cmsSlotBlockEntityManager->deleteCmsSlotBlocks($idCmsSlot, $cmsBlockIds);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfersFromDb
     *
     * @return void
     */
    protected function updateCmsSlotBlockRelations(
        array $cmsSlotBlockTransfers,
        array $cmsSlotBlockTransfersFromDb
    ): void {
        $cmsSlotBlockTransfers = array_intersect_key($cmsSlotBlockTransfers, $cmsSlotBlockTransfersFromDb);

        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $this->cmsSlotBlockEntityManager->updateCmsSlotBlock($cmsSlotBlockTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfersFromDb
     *
     * @return void
     */
    protected function createCmsSlotBlockRelations(
        array $cmsSlotBlockTransfers,
        array $cmsSlotBlockTransfersFromDb
    ): void {
        $cmsSlotBlockTransfers = array_diff_key($cmsSlotBlockTransfers, $cmsSlotBlockTransfersFromDb);

        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $this->cmsSlotBlockEntityManager->createCmsSlotBlock($cmsSlotBlockTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer[][]
     */
    protected function getMappedCmsSlotBlockTransfers(array $cmsSlotBlockTransfers): array
    {
        $mappedCmsSlotBlockTransfers = [];
        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $mappedCmsSlotBlockTransfers[$cmsSlotBlockTransfer->getIdSlot()][$cmsSlotBlockTransfer->getIdCmsBlock()] = $cmsSlotBlockTransfer;
        }

        return $mappedCmsSlotBlockTransfers;
    }
}
