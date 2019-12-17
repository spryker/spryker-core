<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Storage;

use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage;
use Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface;

class GlossaryTranslationStorageWriter implements GlossaryTranslationStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(GlossaryStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
    {
        $this->queryContainer = $queryContainer;
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

        [$glossaryStorageInactiveEntities, $spyGlossaryTranslationEntities] = $this
            ->filterInactiveAndEmptyLocalizedStorageEntities(
                $spyGlossaryTranslationEntities,
                $spyGlossaryStorageEntities
            );

        foreach ($glossaryStorageInactiveEntities as $glossaryStorageInactiveEntity) {
            $glossaryStorageInactiveEntity->delete();
        }

        $this->storeData($spyGlossaryTranslationEntities, $spyGlossaryStorageEntities);
    }

    /**
     * @param array $glossaryTranslations
     * @param array $spyGlossaryTranslationStorageEntities
     *
     * @return array
     */
    protected function filterInactiveAndEmptyLocalizedStorageEntities(array $glossaryTranslations, array $spyGlossaryTranslationStorageEntities): array
    {
        $spyGlossaryStorageEntities = [];
        foreach ($glossaryTranslations as $id => $glossaryTranslation) {
            $idGlossaryKey = $glossaryTranslation['fk_glossary_key'];
            $localeName = $glossaryTranslation['Locale']['locale_name'];

            if ((!$glossaryTranslation['is_active'] || !$glossaryTranslation['value']) &&
                isset($spyGlossaryTranslationStorageEntities[$idGlossaryKey][$localeName])
            ) {
                $spyGlossaryStorageEntities[] = $spyGlossaryTranslationStorageEntities[$idGlossaryKey][$localeName];
                unset($glossaryTranslations[$id]);
            }
        }

        return [$spyGlossaryStorageEntities, $glossaryTranslations];
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

        $spyGlossaryStorage->setFkGlossaryKey($spyGlossaryTranslationEntity['fk_glossary_key']);
        $spyGlossaryStorage->setGlossaryKey($spyGlossaryTranslationEntity['GlossaryKey']['key']);
        $spyGlossaryStorage->setLocale($spyGlossaryTranslationEntity['Locale']['locale_name']);
        $spyGlossaryStorage->setData($spyGlossaryTranslationEntity);
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
