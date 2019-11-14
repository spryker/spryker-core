<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Writer;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\CmsSlotBlock\Business\Exception\InvalidCmsSlotBlockException;
use Spryker\Zed\CmsSlotBlock\Business\Validator\CmsSlotBlockValidatorInterface;
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
     * @var \Spryker\Zed\CmsSlotBlock\Business\Validator\CmsSlotBlockValidatorInterface
     */
    protected $cmsSlotBlockValidator;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager
     * @param \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\CmsSlotBlock\Business\Validator\CmsSlotBlockValidatorInterface $cmsSlotBlockValidator
     */
    public function __construct(
        CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager,
        CmsSlotBlockToEventFacadeInterface $eventFacade,
        CmsSlotBlockValidatorInterface $cmsSlotBlockValidator
    ) {
        $this->cmsSlotBlockEntityManager = $cmsSlotBlockEntityManager;
        $this->eventFacade = $eventFacade;
        $this->cmsSlotBlockValidator = $cmsSlotBlockValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function createCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void
    {
        $cmsSlotBlockTransfers = $cmsSlotBlockCollectionTransfer->getCmsSlotBlocks()->getArrayCopy();
        $this->validateCmsSlotBlockList($cmsSlotBlockTransfers);

        $this->cmsSlotBlockEntityManager->createCmsSlotBlocks($cmsSlotBlockTransfers);
        $this->triggerCmsSlotBlockPublishEvents($cmsSlotBlockTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @throws \Spryker\Zed\CmsSlotBlock\Business\Exception\InvalidCmsSlotBlockException
     *
     * @return void
     */
    protected function validateCmsSlotBlockList(array $cmsSlotBlockTransfers): void
    {
        $isCmsSlotBlockListValid = $this->cmsSlotBlockValidator->getIsCmsSlotBlockListValid($cmsSlotBlockTransfers);

        if (!$isCmsSlotBlockListValid) {
            throw new InvalidCmsSlotBlockException(
                'Some of the CMS slot block are not valid and cannot be saved'
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return void
     */
    public function deleteCmsSlotBlockRelationsByCriteria(CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer): void
    {
        $this->cmsSlotBlockEntityManager->deleteCmsSlotBlocksByCriteria($cmsSlotBlockCriteriaTransfer);
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
