<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 */
abstract class AbstractGlossaryTranslationStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    protected function publish(array $glossaryKeyIds)
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
    protected function unpublish(array $glossaryKeyIds)
    {
        $spyGlossaryTranslationStorageEntities = $this->findGlossaryStorageEntitiesByGlossaryKeyIds($glossaryKeyIds);
        foreach ($spyGlossaryTranslationStorageEntities as $spyGlossaryTranslationStorageEntity) {
            $spyGlossaryTranslationStorageEntity->delete();
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
            } else {
                $this->storeDataSet($spyGlossaryTranslation);
            }
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

        $data = $this->getFactory()->getUtilSanitizeService()->arrayFilterRecursive($spyGlossaryTranslationEntity);
        $spyGlossaryStorage->setFkGlossaryKey($spyGlossaryTranslationEntity['fk_glossary_key']);
        $spyGlossaryStorage->setGlossaryKey($spyGlossaryTranslationEntity['GlossaryKey']['key']);
        $spyGlossaryStorage->setLocale($spyGlossaryTranslationEntity['Locale']['locale_name']);
        $spyGlossaryStorage->setData($data);
        $spyGlossaryStorage->save();
    }

    /**
     * @param array $glossaryKeyIds
     *
     * @return array
     */
    protected function findGlossaryTranslationEntities(array $glossaryKeyIds)
    {
        return $this->getQueryContainer()->queryGlossaryTranslation($glossaryKeyIds)->find()->getData();
    }

    /**
     * @param array $glossaryKeyIds
     *
     * @return array
     */
    protected function findGlossaryStorageEntitiesByGlossaryKeyIds(array $glossaryKeyIds)
    {
        $spyGlossaryStorageEntities = $this->getQueryContainer()->queryGlossaryStorageByGlossaryIds($glossaryKeyIds)->find();
        $glossaryStorageEntitiesByIdAndLocale = [];
        foreach ($spyGlossaryStorageEntities as $spyGlossaryStorageEntity) {
            $glossaryStorageEntitiesByIdAndLocale[$spyGlossaryStorageEntity->getFkGlossaryKey()][$spyGlossaryStorageEntity->getLocale()] = $spyGlossaryStorageEntity;
        }

        return $glossaryStorageEntitiesByIdAndLocale;
    }
}
