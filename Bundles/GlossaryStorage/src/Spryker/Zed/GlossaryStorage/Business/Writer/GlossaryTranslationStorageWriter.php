<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Writer;

use Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer;
use Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToGlossaryFacadeInterface;
use Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface;
use Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface;

class GlossaryTranslationStorageWriter implements GlossaryTranslationStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

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
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface $glossaryStorageRepository
     * @param \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface $glossaryStorageEntityManager
     * @param bool $isSendingToQueue
     */
    public function __construct(
        GlossaryStorageToGlossaryFacadeInterface $glossaryFacade,
        GlossaryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        GlossaryStorageRepositoryInterface $glossaryStorageRepository,
        GlossaryStorageEntityManagerInterface $glossaryStorageEntityManager,
        bool $isSendingToQueue
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->repository = $glossaryStorageRepository;
        $this->entityManager = $glossaryStorageEntityManager;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @deprecated Use `\Spryker\Zed\GlossaryStorage\Business\Writer\GlossaryTranslationStorageWriter::writeGlossaryStorageCollectionByGlossaryKeyEvents()` instead
     *
     * @param int[] $glossaryKeyIds
     *
     * @return void
     */
    public function publish(array $glossaryKeyIds)
    {
        $this->writerGlossaryStorageCollection($glossaryKeyIds);
    }

    /**
     * @deprecated Use `\Spryker\Zed\GlossaryStorage\Business\Writer\GlossaryTranslationStorageWriter::deleteGlossaryStorageCollectionByGlossaryKeyEvents()` instead
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
    public function writeGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers)
    {
        $glossaryKeyIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->writerGlossaryStorageCollection($glossaryKeyIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeGlossaryStorageCollectionByGlossaryTranslationEvents(array $eventTransfers)
    {
        $glossaryKeyIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY);

        $this->writerGlossaryStorageCollection($glossaryKeyIds);
    }

    /**
     * @param int[] $glossaryKeyIds
     *
     * @return void
     */
    protected function writerGlossaryStorageCollection(array $glossaryKeyIds): void
    {
        $glossaryTranslationEntityTransfers = $this->findGlossaryTranslationEntityTransfer($glossaryKeyIds);
        $glossaryStorageEntityTransfers = $this->findGlossaryStorageEntityTransfer($glossaryKeyIds);
        $mappedGlossaryStorageEntityTransfers = $this->mapGlossaryStorageEntityTransferByGlossaryIdAndLocale($glossaryStorageEntityTransfers);

        [$glossaryStorageInactiveEntityTransfer, $glossaryTranslationEntityTransfers] = $this
            ->filterInactiveAndEmptyLocalizedStorageEntityTransfers(
                $glossaryTranslationEntityTransfers,
                $mappedGlossaryStorageEntityTransfers
            );

        /** @var \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer $glossaryStorageInactiveEntity */
        foreach ($glossaryStorageInactiveEntityTransfer as $glossaryStorageInactiveEntity) {
            $this->entityManager->deleteGlossaryStorageEntity((int)$glossaryStorageInactiveEntity->getIdGlossaryStorage());
        }

        $this->storeData($glossaryTranslationEntityTransfers, $mappedGlossaryStorageEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[] $glossaryTranslationEntityTransfers
     * @param array $mappedGlossaryStorageEntityTransfers
     *
     * @return array
     */
    protected function filterInactiveAndEmptyLocalizedStorageEntityTransfers(array $glossaryTranslationEntityTransfers, array $mappedGlossaryStorageEntityTransfers): array
    {
        $glossaryStorageEntityTransfers = [];
        foreach ($glossaryTranslationEntityTransfers as $id => $glossaryTranslationEntityTransfer) {
            $idGlossaryKey = $glossaryTranslationEntityTransfer->getFkGlossaryKey();
            $localeName = $glossaryTranslationEntityTransfer->getLocale()->getLocaleName();

            if ((!$glossaryTranslationEntityTransfer->getIsActive() || !$glossaryTranslationEntityTransfer->getGlossaryKey()->getIsActive() || !$glossaryTranslationEntityTransfer->getValue())) {
                unset($glossaryTranslationEntityTransfers[$id]);

                if (isset($mappedGlossaryStorageEntityTransfers[$idGlossaryKey][$localeName])) {
                    $glossaryStorageEntityTransfers[] = $mappedGlossaryStorageEntityTransfers[$idGlossaryKey][$localeName];
                }
            }
        }

        return [$glossaryStorageEntityTransfers, $glossaryTranslationEntityTransfers];
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
        $glossaryStorageEntityTransfers = $this->findGlossaryStorageEntityTransfer($glossaryKeyIds);
        $mappedGlossaryStorageEntityTransfers = $this->mapGlossaryStorageEntityTransferByGlossaryIdAndLocale($glossaryStorageEntityTransfers);

        foreach ($mappedGlossaryStorageEntityTransfers as $glossaryStorageEntityTransfers) {
            /** @var \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer */
            foreach ($glossaryStorageEntityTransfers as $glossaryStorageEntityTransfer) {
                $this->entityManager->deleteGlossaryStorageEntity((int)$glossaryStorageEntityTransfer->getIdGlossaryStorage());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[] $glossaryTranslationEntityTransfers
     * @param array $mappedGlossaryStorageEntityTransfers
     *
     * @return void
     */
    protected function storeData(array $glossaryTranslationEntityTransfers, array $mappedGlossaryStorageEntityTransfers)
    {
        $glossaryStorageEntityTransfers = [];
        foreach ($glossaryTranslationEntityTransfers as $id => $glossaryTranslationEntityTransfer) {
            $idGlossaryKey = $glossaryTranslationEntityTransfer->getFkGlossaryKey();
            $localeName = $glossaryTranslationEntityTransfer->getLocale()->getLocaleName();
            if (isset($mappedGlossaryStorageEntityTransfers[$idGlossaryKey][$localeName])) {
                $glossaryStorageEntityTransfers[] = $this->storeDataSet($glossaryTranslationEntityTransfer, $mappedGlossaryStorageEntityTransfers[$idGlossaryKey][$localeName]);

                continue;
            }

            $glossaryStorageEntityTransfers[] = $this->storeDataSet($glossaryTranslationEntityTransfer);
        }

        $this->entityManager->saveGlossaryStorageEntities($glossaryStorageEntityTransfers, $this->isSendingToQueue);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer $glossaryTranslationEntityTransfer
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer|null $glossaryStorageEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer
     */
    protected function storeDataSet(SpyGlossaryTranslationEntityTransfer $glossaryTranslationEntityTransfer, ?SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer = null)
    {
        if ($glossaryStorageEntityTransfer === null) {
            $glossaryStorageEntityTransfer = new SpyGlossaryStorageEntityTransfer();
        }

        $glossaryStorageEntityTransfer->setFkGlossaryKey($glossaryTranslationEntityTransfer->getFkGlossaryKey());
        $glossaryStorageEntityTransfer->setGlossaryKey($glossaryTranslationEntityTransfer->getGlossaryKey()->getKey());
        $glossaryStorageEntityTransfer->setLocale($glossaryTranslationEntityTransfer->getLocale()->getLocaleName());

        /**
         * This line added to keep the glossary data structure in backward compatible and
         * will be removed in the next major version.
         *
         * @var string $data https://spryker.atlassian.net/browse/TE-3518
         */
        $data = $this->makeGlossaryDataBackwardCompatible($glossaryTranslationEntityTransfer->modifiedToArray());
        $glossaryStorageEntityTransfer->setData($data);

        return $glossaryStorageEntityTransfer;
    }

    /**
     * @param array $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[]
     */
    protected function findGlossaryTranslationEntityTransfer(array $glossaryKeyIds)
    {
        return $this->glossaryFacade->findGlossaryTranslationEntityTransfer($glossaryKeyIds);
    }

    /**
     * @param array $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[]
     */
    protected function findGlossaryStorageEntityTransfer(array $glossaryKeyIds)
    {
        return $this->repository->findGlossaryStorageEntityTransfer($glossaryKeyIds);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[] $glossaryStorageEntityTransfers
     *
     * @return array
     */
    protected function mapGlossaryStorageEntityTransferByGlossaryIdAndLocale(array $glossaryStorageEntityTransfers)
    {
        $glossaryStorageEntitiesByIdAndLocale = [];
        foreach ($glossaryStorageEntityTransfers as $glossaryStorageEntityTransfer) {
            $glossaryStorageEntitiesByIdAndLocale[$glossaryStorageEntityTransfer->getFkGlossaryKey()][$glossaryStorageEntityTransfer->getLocale()] = $glossaryStorageEntityTransfer;
        }

        return $glossaryStorageEntitiesByIdAndLocale;
    }

    /**
     * @deprecated This method was added to keep the glossary data structure backward compatible and
     * will be removed in the next major version.
     *
     * @param array $data
     *
     * @return array
     */
    protected function makeGlossaryDataBackwardCompatible(array $data): array
    {
        $data['GlossaryKey'] = $data['glossary_key'];
        $data['Locale'] = $data['locale'];

        return $data;
    }
}
