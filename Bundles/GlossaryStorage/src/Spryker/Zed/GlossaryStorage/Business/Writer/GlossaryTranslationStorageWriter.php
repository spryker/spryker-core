<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Writer;

use Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer;
use Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer;
use Spryker\Zed\GlossaryStorage\Business\Mapper\GlossaryTranslationStorageMapperInterface;
use Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface;
use Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface;

class GlossaryTranslationStorageWriter implements GlossaryTranslationStorageWriterInterface
{
    protected const COL_FK_GLOSSARY_KEY = 'spy_glossary_translation.fk_glossary_key';

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
        $glossaryKeyIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, self::COL_FK_GLOSSARY_KEY);

        $this->writerGlossaryStorageCollection($glossaryKeyIds);
    }

    /**
     * @param int[] $glossaryKeyIds
     *
     * @return void
     */
    protected function writerGlossaryStorageCollection(array $glossaryKeyIds): void
    {
        $glossaryTranslationEntityTransfers = $this->repository->findGlossaryTranslationEntityTransfer($glossaryKeyIds);
        $glossaryStorageEntityTransfers = $this->repository->findGlossaryStorageEntityTransfer($glossaryKeyIds);
        $mappedGlossaryStorageEntityTransfers = $this->mapper->mapGlossaryStorageEntityTransferByGlossaryIdAndLocale($glossaryStorageEntityTransfers);

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

        $this->entityManager->saveGlossaryStorageEntities($glossaryStorageEntityTransfers);
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
