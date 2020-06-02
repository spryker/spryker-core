<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Deleter;

use Spryker\Zed\GlossaryStorage\Business\Mapper\GlossaryTranslationStorageMapperInterface;
use Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface;
use Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface;

class GlossaryTranslationStorageDeleter implements GlossaryTranslationStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface
     */
    protected $glossaryStorageRepository;

    /**
     * @var \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface
     */
    protected $glossaryStorageEntityManager;

    /**
     * @var \Spryker\Zed\GlossaryStorage\Business\Mapper\GlossaryTranslationStorageMapperInterface
     */
    protected $glossaryTranslationStorageMapper;

    /**
     * @param \Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface $glossaryStorageRepository
     * @param \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface $glossaryStorageEntityManager
     * @param \Spryker\Zed\GlossaryStorage\Business\Mapper\GlossaryTranslationStorageMapperInterface $glossaryTranslationStorageMapper
     */
    public function __construct(
        GlossaryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        GlossaryStorageRepositoryInterface $glossaryStorageRepository,
        GlossaryStorageEntityManagerInterface $glossaryStorageEntityManager,
        GlossaryTranslationStorageMapperInterface $glossaryTranslationStorageMapper
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->glossaryStorageRepository = $glossaryStorageRepository;
        $this->glossaryStorageEntityManager = $glossaryStorageEntityManager;
        $this->glossaryTranslationStorageMapper = $glossaryTranslationStorageMapper;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\GlossaryStorage\Business\Writer\GlossaryTranslationStorageDeleter::deleteGlossaryStorageCollection()} instead
     *
     * This is added only for BC reasons
     *
     * @param int[] $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds)
    {
        $this->deleteGlossaryStorageCollection($glossaryKeyIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers): void
    {
        $glossaryKeyIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->deleteGlossaryStorageCollection($glossaryKeyIds);
    }

    /**
     * @param int[] $glossaryKeyIds
     *
     * @return void
     */
    protected function deleteGlossaryStorageCollection(array $glossaryKeyIds): void
    {
        $glossaryStorageTransfers = $this->glossaryStorageRepository->findGlossaryStorageEntityTransfer($glossaryKeyIds);
        $mappedGlossaryStorageTransfers = $this->glossaryTranslationStorageMapper->mapGlossaryStorageEntityTransferByGlossaryIdAndLocale($glossaryStorageTransfers);

        foreach ($mappedGlossaryStorageTransfers as $glossaryStorageTransfers) {
            /** @var \Generated\Shared\Transfer\GlossaryStorageTransfer $glossaryStorageTransfer */
            foreach ($glossaryStorageTransfers as $glossaryStorageTransfer) {
                $this->glossaryStorageEntityManager->deleteGlossaryStorageEntity((int)$glossaryStorageTransfer->getIdGlossaryStorage());
            }
        }
    }
}
