<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 */
class AbstractCmsBlockStorageListener extends AbstractPlugin
{
    use DatabaseTransactionHandlerTrait;

    const RELATION_CMS_BLOCK_STORES = 'SpyCmsBlockStores';
    const RELATION_STORE = 'SpyStore';
    const COLUMN_ID_CMS_BLOCK = 'id_cms_block';
    const COLUMN_STORE_NAME = 'name';

    /**
     * Specification:
     * - Aggregates all cms block related data for given cms block IDs
     * - Saves aggregated data to database
     * - Sends aggregated data to synchronization queue
     *
     * @param array $cmsBlockIds
     *
     * @return void
     */
    protected function publish(array $cmsBlockIds)
    {
        $cmsBlockEntities = $this->findCmsBlockEntities($cmsBlockIds);
        $mappedCmsBlockStorageEntities = $this->findCmsBlockStorageEntities($cmsBlockIds);

        $this->storeData($cmsBlockEntities, $mappedCmsBlockStorageEntities);
    }

    /**
     * Specification:
     * - Delete cms stored block data for given cms block IDs
     * - Sends deleted keys to synchronization queue
     *
     * @param array $cmsBlockIds
     *
     * @return void
     */
    protected function unpublish(array $cmsBlockIds)
    {
        $mappedCmsBlockStorageEntities = $this->findCmsBlockStorageEntities($cmsBlockIds);

        $this->deleteCmsBlockStorageEntities($mappedCmsBlockStorageEntities);
    }

    /**
     * @param array $cmsBlockEntities
     * @param array $mappedCmsBlockStorageEntities
     *
     * @return void
     */
    protected function storeData(array $cmsBlockEntities, array $mappedCmsBlockStorageEntities)
    {
        $localeNames = $this->getStore()->getLocales();

        foreach ($cmsBlockEntities as $cmsBlockEntity) {
            $idCmsBlock = $cmsBlockEntity[static::COLUMN_ID_CMS_BLOCK];

            foreach ($localeNames as $localeName) {
                foreach ($cmsBlockEntity[static::RELATION_CMS_BLOCK_STORES] as $cmsBlockStore) {
                    $storeName = $cmsBlockStore[static::RELATION_STORE][static::COLUMN_STORE_NAME];

                    $cmsBlockStorageEntity = $this->findCmsBlockStorageEntity($mappedCmsBlockStorageEntities, $idCmsBlock, $storeName, $localeName);
                    unset($mappedCmsBlockStorageEntities[$idCmsBlock][$storeName][$localeName]);

                    if ($this->isDataStorable($cmsBlockEntity)) {
                        $this->updateStoreData($cmsBlockEntity, $cmsBlockStorageEntity, $storeName, $localeName);

                        continue;
                    }

                    $this->deleteStoreData($cmsBlockStorageEntity);
                }
            }
        }

        $this->deleteCmsBlockStorageEntities($mappedCmsBlockStorageEntities);
    }

    /**
     * @param array $mappedCmsBlockStorageEntities
     *
     * @return void
     */
    protected function deleteCmsBlockStorageEntities(array $mappedCmsBlockStorageEntities)
    {
        foreach ($mappedCmsBlockStorageEntities as $cmsBlockStorageEntitiesPerId) {
            foreach ($cmsBlockStorageEntitiesPerId as $cmsBlockStorageEntitiesPerStore) {
                foreach ($cmsBlockStorageEntitiesPerStore as $cmsBlockStorageEntity) {
                    $cmsBlockStorageEntity->delete();
                }
            }
        }
    }

    /**
     * @param array $mappedCmsBlockStorageEntities
     * @param int $idCmsBlock
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage
     */
    protected function findCmsBlockStorageEntity(array $mappedCmsBlockStorageEntities, $idCmsBlock, $storeName, $localeName)
    {
        if (isset($mappedCmsBlockStorageEntities[$idCmsBlock][$storeName][$localeName])) {
            return $mappedCmsBlockStorageEntities[$idCmsBlock][$storeName][$localeName];
        }

        return new SpyCmsBlockStorage();
    }

    /**
     * @param array $cmsBlockEntity
     *
     * @return bool
     */
    protected function isDataStorable(array $cmsBlockEntity)
    {
        return $cmsBlockEntity['is_active'];
    }

    /**
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage $cmsBlockStorageEntity
     *
     * @return void
     */
    protected function deleteStoreData(SpyCmsBlockStorage $cmsBlockStorageEntity)
    {
        if (!$cmsBlockStorageEntity->isNew()) {
            $cmsBlockStorageEntity->delete();
        }
    }

    /**
     * @param array $cmsBlockEntity
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage $cmsBlockStorageEntity
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function updateStoreData(array $cmsBlockEntity, SpyCmsBlockStorage $cmsBlockStorageEntity, $storeName, $localeName)
    {
        $cmsBlockEntity = $this->prepareDataForSave($cmsBlockEntity, $localeName);

        $cmsBlockStorageEntity
            ->setData($cmsBlockEntity)
            ->setFkCmsBlock($cmsBlockEntity[static::COLUMN_ID_CMS_BLOCK])
            ->setLocale($localeName)
            ->setStore($storeName)
            ->setName($cmsBlockEntity['name'])
            ->save();
    }

    /**
     * @param array $cmsBlockEntity
     * @param string $localeName
     *
     * @return array
     */
    protected function prepareDataForSave(array $cmsBlockEntity, $localeName)
    {
        $cmsBlockEntity = $this->getFactory()->getUtilSanitize()->arrayFilterRecursive($cmsBlockEntity);
        foreach ($this->getFactory()->getContentWidgetDataExpanderPlugins() as $contentWidgetDataExpanderPlugin) {
            $cmsBlockEntity = $contentWidgetDataExpanderPlugin->expand($cmsBlockEntity, $localeName);
        }

        return $cmsBlockEntity;
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock[]
     */
    protected function findCmsBlockEntities(array $cmsBlockIds)
    {
        return $this->getQueryContainer()->queryBlockWithRelationsByIds($cmsBlockIds)->find()->getData();
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage[]
     */
    protected function findCmsBlockStorageEntities(array $cmsBlockIds)
    {
        $cmsBlockStorageEntities = $this->getQueryContainer()->queryCmsBlockStorageEntities($cmsBlockIds)->find()->getArrayCopy();

        return $this->mapCmsBlockStorageEntities($cmsBlockStorageEntities);
    }

    /**
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage[] $cmsBlockStorageEntities
     *
     * @return array
     */
    protected function mapCmsBlockStorageEntities(array $cmsBlockStorageEntities)
    {
        $mappedCmsBlockStorageEntities = [];
        foreach ($cmsBlockStorageEntities as $cmsBlockStorageEntity) {
            $mappedCmsBlockStorageEntities[$cmsBlockStorageEntity->getFkCmsBlock()][$cmsBlockStorageEntity->getStore()][$cmsBlockStorageEntity->getLocale()] = $cmsBlockStorageEntity;
        }

        return $mappedCmsBlockStorageEntities;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }
}
