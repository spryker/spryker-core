<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Writer;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\CmsSlotBlock\Dependency\CmsSlotBlockEvents;
use Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToEventFacadeInterface;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface;

class CmsSlotBlockRelationsWriter implements CmsSlotBlockRelationsWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface
     */
    protected $cmsSlotBlockEntityManager;

    /**
     * @var \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager
     * @param \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToEventFacadeInterface $eventFacade
     */
    public function __construct(
        CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager,
        CmsSlotBlockToEventFacadeInterface $eventFacade
    ) {
        $this->cmsSlotBlockEntityManager = $cmsSlotBlockEntityManager;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function saveCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void
    {
        $cmsSlotBlockTransfers = $cmsSlotBlockCollectionTransfer->getCmsSlotBlocks()->getArrayCopy();

        $this->deleteCmsSlotBlocks($cmsSlotBlockTransfers);
        $this->cmsSlotBlockEntityManager->createCmsSlotBlocks($cmsSlotBlockTransfers);
        $this->triggerCmsSlotBlockPublishEvents($cmsSlotBlockTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return void
     */
    protected function deleteCmsSlotBlocks(array $cmsSlotBlockTransfers): void
    {
        $mappedCmsSlotBlockTransfers = $this->getMappedCmsSlotBlockTransfersByIdSlotTemplate($cmsSlotBlockTransfers);
        foreach ($mappedCmsSlotBlockTransfers as $idSlotTemplate => $cmsSlotBlockTransfers) {
            $cmsSlotIds = $this->getUniqueCmsSlotIds($cmsSlotBlockTransfers);
            $this->cmsSlotBlockEntityManager->deleteCmsSlotBlocks($idSlotTemplate, $cmsSlotIds);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer[][]
     */
    protected function getMappedCmsSlotBlockTransfersByIdSlotTemplate(array $cmsSlotBlockTransfers): array
    {
        $mappedCmsSlotBlockTransfers = [];
        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $mappedCmsSlotBlockTransfers[$cmsSlotBlockTransfer->getIdSlotTemplate()][] = $cmsSlotBlockTransfer;
        }

        return $mappedCmsSlotBlockTransfers;
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
            $idCmsSlot = $cmsSlotBlockTransfer->getIdSlot();

            if (!isset($cmsSlotIds[$idCmsSlot])) {
                $cmsSlotIds[$idCmsSlot] = $idCmsSlot;
            }
        }

        return $cmsSlotIds;
    }

    /**
     * @param array $cmsSlotBlockTransfers
     *
     * @return void
     */
    protected function triggerCmsSlotBlockPublishEvents(array $cmsSlotBlockTransfers): void
    {
        $eventTransfers = $this->mapCmsSlotBlockTransfersToEventTransfers($cmsSlotBlockTransfers);
        $this->eventFacade->triggerBulk(CmsSlotBlockEvents::CMS_SLOT_BLOCK_PUBLISH, $eventTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    protected function mapCmsSlotBlockTransfersToEventTransfers(array $cmsSlotBlockTransfers): array
    {
        $eventTransfers = [];

        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())
                ->setId($cmsSlotBlockTransfer->getIdCmsBlock());
        }

        return $eventTransfers;
    }
}
