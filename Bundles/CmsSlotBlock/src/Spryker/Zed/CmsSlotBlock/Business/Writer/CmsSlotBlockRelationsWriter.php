<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Writer;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\CmsSlotBlock\Business\Exception\InvalidCmsSlotBlockException;
use Spryker\Zed\CmsSlotBlock\Business\Validator\CmsSlotBlockValidatorInterface;
use Spryker\Zed\CmsSlotBlock\Dependency\CmsSlotBlockEvents;
use Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToEventFacadeInterface;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface;

class CmsSlotBlockRelationsWriter implements CmsSlotBlockRelationsWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface
     */
    protected $cmsSlotBlockEntityManager;

    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface
     */
    protected $cmsSlotBlockRepository;

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
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface $cmsSlotBlockRepository
     * @param \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\CmsSlotBlock\Business\Validator\CmsSlotBlockValidatorInterface $cmsSlotBlockValidator
     */
    public function __construct(
        CmsSlotBlockEntityManagerInterface $cmsSlotBlockEntityManager,
        CmsSlotBlockRepositoryInterface $cmsSlotBlockRepository,
        CmsSlotBlockToEventFacadeInterface $eventFacade,
        CmsSlotBlockValidatorInterface $cmsSlotBlockValidator
    ) {
        $this->cmsSlotBlockEntityManager = $cmsSlotBlockEntityManager;
        $this->cmsSlotBlockRepository = $cmsSlotBlockRepository;
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
        $this->assureCmsSlotBlockListIsValid($cmsSlotBlockTransfers);

        $cmsSlotBlockCollectionTransfer = $this->cmsSlotBlockEntityManager->createCmsSlotBlocks($cmsSlotBlockTransfers);
        $this->triggerCmsSlotBlockPublishEvents($cmsSlotBlockCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @throws \Spryker\Zed\CmsSlotBlock\Business\Exception\InvalidCmsSlotBlockException
     *
     * @return void
     */
    protected function assureCmsSlotBlockListIsValid(array $cmsSlotBlockTransfers): void
    {
        $validationResponseTransfer = $this->cmsSlotBlockValidator->getIsCmsSlotBlockListValid($cmsSlotBlockTransfers);

        if (!$validationResponseTransfer->getIsSuccess()) {
            throw new InvalidCmsSlotBlockException(
                $validationResponseTransfer->getErrorMessages()[0]->getValue()
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
        $cmsSlotBlockCollectionTransfer = $this->cmsSlotBlockRepository
            ->getCmsSlotBlocks($cmsSlotBlockCriteriaTransfer);
        $this->triggerCmsSlotBlockPublishEvents($cmsSlotBlockCollectionTransfer);
        $this->cmsSlotBlockEntityManager->deleteCmsSlotBlocksByCriteria($cmsSlotBlockCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    protected function triggerCmsSlotBlockPublishEvents(
        CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
    ): void {
        $eventTransfers = $this->mapCmsSlotBlockCollectionTransferToEventTransfers($cmsSlotBlockCollectionTransfer);
        $this->eventFacade->triggerBulk(CmsSlotBlockEvents::CMS_SLOT_BLOCK_PUBLISH, $eventTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    protected function mapCmsSlotBlockCollectionTransferToEventTransfers(
        CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
    ): array {
        $eventTransfers = [];

        foreach ($cmsSlotBlockCollectionTransfer->getCmsSlotBlocks() as $cmsSlotBlockTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())
                ->setId((int)$cmsSlotBlockTransfer->getIdCmsSlotBlock())
                ->setAdditionalValues([
                    CmsSlotBlockTransfer::ID_SLOT_TEMPLATE => $cmsSlotBlockTransfer->getIdSlotTemplate(),
                    CmsSlotBlockTransfer::ID_SLOT => $cmsSlotBlockTransfer->getIdSlot(),
                ]);
        }

        return $eventTransfers;
    }
}
