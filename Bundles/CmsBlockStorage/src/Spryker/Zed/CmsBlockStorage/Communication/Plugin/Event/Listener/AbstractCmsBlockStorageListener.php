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
    const COLUMN_CMS_BLOCK_NAME = 'name';
    const COLUMN_CMS_BLOCK_IS_ACTIVE = 'is_active';

    const PAIR_CMS_BLOCK_ENTITY = 'CmsBlockEntity';
    const PAIR_CMS_BLOCK_STORAGE_ENTITY = 'CmsBlockStorageEntity';
    const PAIR_LOCALE_NAME = 'storeName';
    const PAIR_STORE_NAME = 'localeName';

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
        $cmsBlockStorageEntities = $this->findCmsBlockStorageEntities($cmsBlockIds);

        if (!$cmsBlockEntities) {
            $this->deleteStorageEntities($cmsBlockStorageEntities);

            return;
        }

        $pairedEntities = $this->pairCmsBlockEntityWithCmsBlockStorageEntity(
            $cmsBlockEntities,
            $cmsBlockStorageEntities
        );

        $this->storeData($pairedEntities);
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
        $cmsBlockStorageEntities = $this->findCmsBlockStorageEntities($cmsBlockIds);

        $this->deleteStorageEntities($cmsBlockStorageEntities);
    }

    /**
     * @param array $pairedEntities
     *
     * @return void
     */
    protected function storeData(array $pairedEntities)
    {
        foreach ($pairedEntities as $pair) {
            $cmsBlockEntity = $pair[static::PAIR_CMS_BLOCK_ENTITY];
            $cmsBlockStorageEntity = $pair[static::PAIR_CMS_BLOCK_STORAGE_ENTITY];
            $storeName = $pair[static::PAIR_STORE_NAME];
            $localeName = $pair[static::PAIR_LOCALE_NAME];

            if ($cmsBlockEntity === null || !$cmsBlockEntity[static::COLUMN_CMS_BLOCK_IS_ACTIVE]) {
                $this->deleteStorageEntity($cmsBlockStorageEntity);

                continue;
            }

            $this->updateStoreData($cmsBlockEntity, $cmsBlockStorageEntity, $storeName, $localeName);
        }
    }

    /**
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage[] $cmsBlockStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $cmsBlockStorageEntities)
    {
        foreach ($cmsBlockStorageEntities as $cmsBlockStorageEntity) {
            $cmsBlockStorageEntity->delete();
        }
    }

    /**
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage $cmsBlockStorageEntity
     *
     * @return void
     */
    protected function deleteStorageEntity(SpyCmsBlockStorage $cmsBlockStorageEntity)
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
        $cmsBlockEntity = $this->getFactory()->getUtilSanitize()->arrayFilterRecursive($cmsBlockEntity);
        foreach ($this->getFactory()->getContentWidgetDataExpanderPlugins() as $contentWidgetDataExpanderPlugin) {
            $cmsBlockEntity = $contentWidgetDataExpanderPlugin->expand($cmsBlockEntity, $localeName);
        }

        $cmsBlockStorageEntity
            ->setData($cmsBlockEntity)
            ->setFkCmsBlock($cmsBlockEntity[static::COLUMN_ID_CMS_BLOCK])
            ->setLocale($localeName)
            ->setStore($storeName)
            ->setName($cmsBlockEntity[static::COLUMN_CMS_BLOCK_NAME])
            ->save();
    }

    /**
     * - Returns a paired array with all provided entities.
     * - CmsBlockEntities without CmsBlockStorageEntities are paired with a newly created CmsBlockStorageEntity.
     * - CmsBlockStorageEntities without CmsBlockEntities (left outs) are paired with NULL.
     * - CmsBlockEntities are paired multiple times per locales and per stores.
     *
     * @param array $cmsBlockEntities
     * @param array $cmsBlockStorageEntities
     *
     * @return array
     */
    protected function pairCmsBlockEntityWithCmsBlockStorageEntity(array $cmsBlockEntities, array $cmsBlockStorageEntities)
    {
        $mappedCmsBlockStorageEntities = $this->mapCmsBlockStorageEntities($cmsBlockStorageEntities);
        $localeNames = $this->getStore()->getLocales();

        $pairs = [];
        foreach ($cmsBlockEntities as $cmsBlockEntity) {
            list($mappedCmsBlockStorageEntities, $pairs) = $this->pairCmsBlockEntityWithCmsBlockStorageEntityByLocalesAndStores(
                $cmsBlockEntity[static::COLUMN_ID_CMS_BLOCK],
                $localeNames,
                $cmsBlockEntity[static::RELATION_CMS_BLOCK_STORES],
                $mappedCmsBlockStorageEntities,
                $cmsBlockEntity,
                $pairs
            );
        }

        $pairs = $this->pairRemainingCmsBlockStorageEntities($mappedCmsBlockStorageEntities, $pairs);

        return $pairs;
    }

    /**
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage[] $cmsBlockStorageEntities
     *
     * @return array
     */
    protected function mapCmsBlockStorageEntities(array $cmsBlockStorageEntities)
    {
        $map = [];
        foreach ($cmsBlockStorageEntities as $cmsBlockStorageEntity) {
            $map[$cmsBlockStorageEntity->getFkCmsBlock()][$cmsBlockStorageEntity->getLocale()][$cmsBlockStorageEntity->getStore()] = $cmsBlockStorageEntity;
        }
        
        return $map;
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return array
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
        return $this->getQueryContainer()->queryCmsBlockStorageEntities($cmsBlockIds)->find()->getArrayCopy();
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }

    /**
     * @param array $mappedCmsBlockStorageEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairRemainingCmsBlockStorageEntities(array $mappedCmsBlockStorageEntities, array $pairs)
    {
        array_walk_recursive($mappedCmsBlockStorageEntities, function (SpyCmsBlockStorage $cmsBlockStorageEntity) use (&$pairs) {
            $pairs[] = [
                static::PAIR_CMS_BLOCK_ENTITY => null,
                static::PAIR_CMS_BLOCK_STORAGE_ENTITY => $cmsBlockStorageEntity,
                static::PAIR_LOCALE_NAME => $cmsBlockStorageEntity->getLocale(),
                static::PAIR_STORE_NAME => $cmsBlockStorageEntity->getStore(),
            ];
        });

        return $pairs;
    }

    /**
     * @param int $idCmsBlock
     * @param array $localeNames
     * @param array $cmsBlockStores
     * @param array $mappedCmsBlockStorageEntities
     * @param array $cmsBlockEntity
     * @param array $pairs
     *
     * @return array
     */
    protected function pairCmsBlockEntityWithCmsBlockStorageEntityByLocalesAndStores(
        $idCmsBlock,
        array $localeNames,
        array $cmsBlockStores,
        array $mappedCmsBlockStorageEntities,
        array $cmsBlockEntity,
        array $pairs
    ) {
        foreach ($localeNames as $localeName) {
            foreach ($cmsBlockStores as $cmsBlockStore) {
                $storeName = $cmsBlockStore[static::RELATION_STORE][static::COLUMN_STORE_NAME];

                $cmsBlockStorageEntity = isset($mappedCmsBlockStorageEntities[$idCmsBlock][$localeName][$storeName]) ?
                    $mappedCmsBlockStorageEntities[$idCmsBlock][$localeName][$storeName] :
                    new SpyCmsBlockStorage();

                $pairs[] = [
                    static::PAIR_CMS_BLOCK_ENTITY => $cmsBlockEntity,
                    static::PAIR_CMS_BLOCK_STORAGE_ENTITY => $cmsBlockStorageEntity,
                    static::PAIR_LOCALE_NAME => $localeName,
                    static::PAIR_STORE_NAME => $storeName,
                ];

                unset($mappedCmsBlockStorageEntities[$idCmsBlock][$localeName][$storeName]);
            }
        }

        return [$mappedCmsBlockStorageEntities, $pairs];
    }
}
