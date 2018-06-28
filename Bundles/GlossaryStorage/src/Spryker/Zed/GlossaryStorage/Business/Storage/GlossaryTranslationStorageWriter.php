<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Storage;

use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage;
use Spryker\Zed\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface;

class GlossaryTranslationStorageWriter implements GlossaryTranslationStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilSanitizeServiceInterface $utilSanitizeService
     * @param bool $isSendingToQueue
     */
    public function __construct(GlossaryStorageQueryContainerInterface $queryContainer, GlossaryStorageToUtilSanitizeServiceInterface $utilSanitizeService, $isSendingToQueue)
    {
        $this->queryContainer = $queryContainer;
        $this->utilSanitizeService = $utilSanitizeService;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function publish(array $glossaryKeyIds)
    {
        $spyGlossaryTranslationEntities = $this->findGlossaryTranslationEntities($glossaryKeyIds);
        $spyGlossaryStorageEntities = $this->findGlossaryStorageEntitiesByGlossaryKeyIds($glossaryKeyIds);

        $this->storeData($spyGlossaryTranslationEntities, $spyGlossaryStorageEntities);
    }

    /**
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds)
    {
        $spyGlossaryTranslationStorageEntities = $this->findGlossaryStorageEntitiesByGlossaryKeyIds($glossaryKeyIds);
        foreach ($spyGlossaryTranslationStorageEntities as $spyGlossaryTranslationStorageLocalizedEntities) {
            foreach ($spyGlossaryTranslationStorageLocalizedEntities as $spyGlossaryTranslationStorageLocalizedEntity) {
                $spyGlossaryTranslationStorageLocalizedEntity->delete();
            }
        }
    }

    /**
     * @param array $spyGlossaryTranslationEntities
     * @param array $spyGlossaryTranslationStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyGlossaryTranslationEntities, array $spyGlossaryTranslationStorageEntities)
    {
        foreach ($spyGlossaryTranslationEntities as $spyGlossaryTranslation) {
            $idGlossaryKey = $spyGlossaryTranslation['fk_glossary_key'];
            $localeName = $spyGlossaryTranslation['Locale']['locale_name'];
            if (isset($spyGlossaryTranslationStorageEntities[$idGlossaryKey][$localeName])) {
                $this->storeDataSet($spyGlossaryTranslation, $spyGlossaryTranslationStorageEntities[$idGlossaryKey][$localeName]);

                continue;
            }

            $this->storeDataSet($spyGlossaryTranslation);
        }
    }

    /**
     * @param array $spyGlossaryTranslationEntity
     * @param \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage|null $spyGlossaryStorage
     *
     * @return void
     */
    protected function storeDataSet(array $spyGlossaryTranslationEntity, ?SpyGlossaryStorage $spyGlossaryStorage = null)
    {
        if ($spyGlossaryStorage === null) {
            $spyGlossaryStorage = new SpyGlossaryStorage();
        }

        $data = $this->utilSanitizeService->arrayFilterRecursive($spyGlossaryTranslationEntity);
        $spyGlossaryStorage->setFkGlossaryKey($spyGlossaryTranslationEntity['fk_glossary_key']);
        $spyGlossaryStorage->setGlossaryKey($spyGlossaryTranslationEntity['GlossaryKey']['key']);
        $spyGlossaryStorage->setLocale($spyGlossaryTranslationEntity['Locale']['locale_name']);
        $spyGlossaryStorage->setData($data);
        $spyGlossaryStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyGlossaryStorage->save();
    }

    /**
     * @param array $glossaryKeyIds
     *
     * @return array
     */
    protected function findGlossaryTranslationEntities(array $glossaryKeyIds)
    {
        return $this->queryContainer->queryGlossaryTranslation($glossaryKeyIds)->find()->getData();
    }

    /**
     * @param array $glossaryKeyIds
     *
     * @return array
     */
    protected function findGlossaryStorageEntitiesByGlossaryKeyIds(array $glossaryKeyIds)
    {
        $spyGlossaryStorageEntities = $this->queryContainer->queryGlossaryStorageByGlossaryIds($glossaryKeyIds)->find();
        $glossaryStorageEntitiesByIdAndLocale = [];
        foreach ($spyGlossaryStorageEntities as $spyGlossaryStorageEntity) {
            $glossaryStorageEntitiesByIdAndLocale[$spyGlossaryStorageEntity->getFkGlossaryKey()][$spyGlossaryStorageEntity->getLocale()] = $spyGlossaryStorageEntity;
        }

        return $glossaryStorageEntitiesByIdAndLocale;
    }
}
