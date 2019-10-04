<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Business\Storage;

use Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage;
use Spryker\Zed\CmsBlockStorage\Dependency\Facade\CmsBlockStorageToStoreFacadeInterface;
use Spryker\Zed\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockStorageWriter implements CmsBlockStorageWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    public const RELATION_CMS_BLOCK_STORES = 'SpyCmsBlockStores';
    public const RELATION_STORE = 'SpyStore';
    public const COLUMN_ID_CMS_BLOCK = 'id_cms_block';
    public const COLUMN_STORE_NAME = 'name';
    public const COLUMN_CMS_BLOCK_NAME = 'name';
    public const COLUMN_CMS_BLOCK_IS_ACTIVE = 'is_active';

    public const CMS_BLOCK_ENTITY = 'CMS_BLOCK_ENTITY';
    public const CMS_BLOCK_STORAGE_ENTITY = 'CMS_BLOCK_STORAGE_ENTITY';
    public const LOCALE_NAME = 'LOCALE_NAME';
    public const STORE_NAME = 'STORE_NAME';

    /**
     * @var \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSanitizeServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\CmsBlockStorage\Dependency\Plugin\CmsBlockStorageDataExpanderPluginInterface[]
     */
    protected $contentWidgetDataExpanderPlugins = [];

    /**
     * @var \Spryker\Zed\CmsBlockStorage\Dependency\Facade\CmsBlockStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSanitizeServiceInterface $utilEncodingService
     * @param \Spryker\Zed\CmsBlockStorage\Dependency\Plugin\CmsBlockStorageDataExpanderPluginInterface[] $contentWidgetDataExpanderPlugins
     * @param \Spryker\Zed\CmsBlockStorage\Dependency\Facade\CmsBlockStorageToStoreFacadeInterface $storeFacade
     * @param bool $isSendingToQueue
     */
    public function __construct(
        CmsBlockStorageQueryContainerInterface $queryContainer,
        CmsBlockStorageToUtilSanitizeServiceInterface $utilEncodingService,
        array $contentWidgetDataExpanderPlugins,
        CmsBlockStorageToStoreFacadeInterface $storeFacade,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->contentWidgetDataExpanderPlugins = $contentWidgetDataExpanderPlugins;
        $this->storeFacade = $storeFacade;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return void
     */
    public function publish(array $cmsBlockIds)
    {
        $cmsBlockEntities = $this->findCmsBlockEntities($cmsBlockIds);
        $cmsBlockStorageEntities = $this->findCmsBlockStorageEntities($cmsBlockIds);

        if (!$cmsBlockEntities) {
            $this->deleteStorageEntities($cmsBlockStorageEntities);

            return;
        }

        $this->storeData($cmsBlockEntities, $cmsBlockStorageEntities);
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
    public function unpublish(array $cmsBlockIds)
    {
        $cmsBlockStorageEntities = $this->findCmsBlockStorageEntities($cmsBlockIds);

        $this->deleteStorageEntities($cmsBlockStorageEntities);
    }

    /**
     * @param array $cmsBlockEntities
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage[] $cmsBlockStorageEntities
     *
     * @return void
     */
    protected function storeData(array $cmsBlockEntities, array $cmsBlockStorageEntities)
    {
        $pairedEntities = $this->pairCmsBlockEntitiesWithCmsBlockStorageEntities(
            $cmsBlockEntities,
            $cmsBlockStorageEntities
        );

        $storeTransfer = $this->storeFacade->getCurrentStore();
        $storesWithSharedPersistence = $storeTransfer->getStoresWithSharedPersistence();
        $storeName = $storeTransfer->getName();
        $storeRelations = array_merge($storesWithSharedPersistence, [$storeName]);

        foreach ($pairedEntities as $pair) {
            $cmsBlockEntity = $pair[static::CMS_BLOCK_ENTITY];
            $cmsBlockStorageEntity = $pair[static::CMS_BLOCK_STORAGE_ENTITY];

            if (!in_array($pair[static::STORE_NAME], $storeRelations, true)) {
                $this->deleteStorageEntity($cmsBlockStorageEntity);
                continue;
            }

            if ($cmsBlockEntity === null || !$cmsBlockEntity[static::COLUMN_CMS_BLOCK_IS_ACTIVE]) {
                $this->deleteStorageEntity($cmsBlockStorageEntity);

                continue;
            }

            $this->updateStoreData(
                $cmsBlockEntity,
                $cmsBlockStorageEntity,
                $pair[static::STORE_NAME],
                $pair[static::LOCALE_NAME]
            );
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
        foreach ($this->contentWidgetDataExpanderPlugins as $contentWidgetDataExpanderPlugin) {
            $cmsBlockEntity = $contentWidgetDataExpanderPlugin->expand($cmsBlockEntity, $localeName);
        }

        $cmsBlockStorageEntity
            ->setData($cmsBlockEntity)
            ->setFkCmsBlock($cmsBlockEntity[static::COLUMN_ID_CMS_BLOCK])
            ->setLocale($localeName)
            ->setStore($storeName)
            ->setName($cmsBlockEntity[static::COLUMN_CMS_BLOCK_NAME])
            ->setIsSendingToQueue($this->isSendingToQueue)
            ->save();
    }

    /**
     * - Returns a paired array with all provided entities.
     * - CmsBlockEntities without CmsBlockStorageEntity are paired with a newly created CmsBlockStorageEntity.
     * - CmsBlockStorageEntities without CmsBlockEntity (left outs) are paired with NULL.
     * - CmsBlockEntities are paired multiple times per locale and per store.
     *
     * @param array $cmsBlockEntities
     * @param array $cmsBlockStorageEntities
     *
     * @return array
     */
    protected function pairCmsBlockEntitiesWithCmsBlockStorageEntities(array $cmsBlockEntities, array $cmsBlockStorageEntities)
    {
        $mappedCmsBlockStorageEntities = $this->mapCmsBlockStorageEntities($cmsBlockStorageEntities);
        $localeNameMap = $this->getLocaleNameMapByStoreName();

        $pairs = [];
        foreach ($cmsBlockEntities as $cmsBlockEntity) {
            [$pairs, $mappedCmsBlockStorageEntities] = $this->pairCmsBlockEntityWithCmsBlockStorageEntitiesByLocalesAndStores(
                $cmsBlockEntity[static::COLUMN_ID_CMS_BLOCK],
                $localeNameMap,
                $cmsBlockEntity[static::RELATION_CMS_BLOCK_STORES],
                $cmsBlockEntity,
                $mappedCmsBlockStorageEntities,
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
        $mappedCmsBlockStorageEntities = [];
        foreach ($cmsBlockStorageEntities as $entity) {
            $mappedCmsBlockStorageEntities[$entity->getFkCmsBlock()][$entity->getLocale()][$entity->getStore()] = $entity;
        }

        return $mappedCmsBlockStorageEntities;
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return array
     */
    protected function findCmsBlockEntities(array $cmsBlockIds)
    {
        return $this->queryContainer->queryBlockWithRelationsByIds($cmsBlockIds)->find()->getData();
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage[]
     */
    protected function findCmsBlockStorageEntities(array $cmsBlockIds)
    {
        return $this->queryContainer->queryCmsBlockStorageEntities($cmsBlockIds)->find()->getArrayCopy();
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
                static::CMS_BLOCK_ENTITY => null,
                static::CMS_BLOCK_STORAGE_ENTITY => $cmsBlockStorageEntity,
                static::LOCALE_NAME => $cmsBlockStorageEntity->getLocale(),
                static::STORE_NAME => $cmsBlockStorageEntity->getStore(),
            ];
        });

        return $pairs;
    }

    /**
     * @param int $idCmsBlock
     * @param string[][] $localeNameMap
     * @param array $cmsBlockStores
     * @param array $cmsBlockEntity
     * @param array $mappedCmsBlockStorageEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairCmsBlockEntityWithCmsBlockStorageEntitiesByLocalesAndStores(
        $idCmsBlock,
        array $localeNameMap,
        array $cmsBlockStores,
        array $cmsBlockEntity,
        array $mappedCmsBlockStorageEntities,
        array $pairs
    ) {
        foreach ($cmsBlockStores as $cmsBlockStore) {
            $storeName = $cmsBlockStore[static::RELATION_STORE][static::COLUMN_STORE_NAME];
            $localeNames = $localeNameMap[$storeName];

            foreach ($localeNames as $localeName) {
                $cmsBlockStorageEntity = isset($mappedCmsBlockStorageEntities[$idCmsBlock][$localeName][$storeName]) ?
                    $mappedCmsBlockStorageEntities[$idCmsBlock][$localeName][$storeName] :
                    new SpyCmsBlockStorage();

                $pairs[] = [
                    static::CMS_BLOCK_ENTITY => $cmsBlockEntity,
                    static::CMS_BLOCK_STORAGE_ENTITY => $cmsBlockStorageEntity,
                    static::LOCALE_NAME => $localeName,
                    static::STORE_NAME => $storeName,
                ];

                unset($mappedCmsBlockStorageEntities[$idCmsBlock][$localeName][$storeName]);
            }
        }

        return [$pairs, $mappedCmsBlockStorageEntities];
    }

    /**
     * @return string[][]
     */
    protected function getLocaleNameMapByStoreName(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $localeNameMapByStoreName = [];

        foreach ($storeTransfers as $storeTransfer) {
            $localeNameMapByStoreName[$storeTransfer->getName()] = $storeTransfer->getAvailableLocaleIsoCodes();
        }

        return $localeNameMapByStoreName;
    }
}
