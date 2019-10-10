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
    protected $repository;

    /**
     * @var \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\GlossaryStorage\Business\Mapper\GlossaryTranslationStorageMapperInterface
     */
    protected $mapper;

    /**
     * @param \Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface $glossaryStorageRepository
     * @param \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface $glossaryStorageEntityManager
     * @param \Spryker\Zed\GlossaryStorage\Business\Mapper\GlossaryTranslationStorageMapperInterface $mapper
     */
    public function __construct(
        GlossaryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        GlossaryStorageRepositoryInterface $glossaryStorageRepository,
        GlossaryStorageEntityManagerInterface $glossaryStorageEntityManager,
        GlossaryTranslationStorageMapperInterface $mapper
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->repository = $glossaryStorageRepository;
        $this->entityManager = $glossaryStorageEntityManager;
        $this->mapper = $mapper;
    }

    /**
     * @deprecated Use `\Spryker\Zed\GlossaryStorage\Business\Writer\GlossaryTranslationStorageDeleter::deleteGlossaryStorageCollection()` instead
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
     * @param array $eventTransfers
     *
     * @return void
     */
    public function deleteGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers)
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
        $glossaryStorageTransfers = $this->repository->findGlossaryStorageEntityTransfer($glossaryKeyIds);
        $mappedGlossaryStorageTransfers = $this->mapper->mapGlossaryStorageEntityTransferByGlossaryIdAndLocale($glossaryStorageTransfers);

        foreach ($mappedGlossaryStorageTransfers as $glossaryStorageTransfers) {
            /** @var \Generated\Shared\Transfer\GlossaryStorageTransfer $glossaryStorageTransfer */
            foreach ($glossaryStorageTransfers as $glossaryStorageTransfer) {
                $this->entityManager->deleteGlossaryStorageEntity((int)$glossaryStorageTransfer->getIdGlossaryStorage());
            }
        }
    }
}
