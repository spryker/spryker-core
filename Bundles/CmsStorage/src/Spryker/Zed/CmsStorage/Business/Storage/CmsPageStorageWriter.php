<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Business\Storage;

use DateTime;
use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage;
use Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToCmsInterface;
use Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToStoreFacadeInterface;
use Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface;

class CmsPageStorageWriter implements CmsPageStorageWriterInterface
{
    protected const CMS_PAGE_ENTITY = 'CMS_PAGE_ENTITY';
    protected const CMS_PAGE_STORAGE_ENTITY = 'CMS_PAGE_STORAGE_ENTITY';
    protected const LOCALE_NAME = 'LOCALE_NAME';
    protected const STORE_NAME = 'STORE_NAME';

    /**
     * @var \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\CmsExtension\Dependency\Plugin\CmsPageDataExpanderPluginInterface[]
     */
    protected $contentWidgetDataExpanderPlugins = [];

    /**
     * @var \Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToCmsInterface $cmsFacade
     * @param \Spryker\Zed\CmsExtension\Dependency\Plugin\CmsPageDataExpanderPluginInterface[] $contentWidgetDataExpanderPlugins
     * @param \Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToStoreFacadeInterface $storeFacade
     * @param bool $isSendingToQueue
     */
    public function __construct(
        CmsStorageQueryContainerInterface $queryContainer,
        CmsStorageToCmsInterface $cmsFacade,
        array $contentWidgetDataExpanderPlugins,
        CmsStorageToStoreFacadeInterface $storeFacade,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->cmsFacade = $cmsFacade;
        $this->contentWidgetDataExpanderPlugins = $contentWidgetDataExpanderPlugins;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int[] $cmsPageIds
     *
     * @return void
     */
    public function publish(array $cmsPageIds): void
    {
        $cmsPageEntities = $this->findCmsPageEntities($cmsPageIds);
        $cmsPageStorageEntities = $this->findCmsStorageEntities($cmsPageIds);

        $this->storeData($cmsPageEntities, $cmsPageStorageEntities);
    }

    /**
     * @param int[] $cmsPageIds
     *
     * @return void
     */
    public function unpublish(array $cmsPageIds): void
    {
        $cmsPageStorageEntities = $this->findCmsStorageEntities($cmsPageIds);
        $this->deleteStorageEntities($cmsPageStorageEntities);
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage[] $cmsPageEntities
     * @param array $cmsPageStorageEntities
     *
     * @return void
     */
    protected function storeData(array $cmsPageEntities, array $cmsPageStorageEntities): void
    {
        $pairedEntities = $this->pairCmsPageEntitiesWithCmsPageStorageEntities(
            $cmsPageEntities,
            $cmsPageStorageEntities
        );

        $storeTransfer = $this->storeFacade->getCurrentStore();
        $storesWithSharedPersistence = $storeTransfer->getStoresWithSharedPersistence();
        $storeName = $storeTransfer->getName();
        $storeRelations = array_merge($storesWithSharedPersistence, [$storeName]);

        foreach ($pairedEntities as $pair) {
            $cmsPageEntity = $pair[static::CMS_PAGE_ENTITY];
            $cmsPageStorageEntity = $pair[static::CMS_PAGE_STORAGE_ENTITY];

            if (!in_array($pair[static::STORE_NAME], $storeRelations, true)) {
                $this->deleteStorageEntity($cmsPageStorageEntity);
                continue;
            }

            if ($cmsPageEntity === null || !$cmsPageEntity->getIsActive()) {
                $this->deleteStorageEntity($cmsPageStorageEntity);

                continue;
            }

            $this->storeDataSet(
                $cmsPageEntity,
                $cmsPageStorageEntity,
                $pair[static::LOCALE_NAME],
                $pair[static::STORE_NAME]
            );
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param \Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage $cmsPageStorageEntity
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return void
     */
    protected function storeDataSet(
        SpyCmsPage $cmsPageEntity,
        SpyCmsPageStorage $cmsPageStorageEntity,
        string $localeName,
        ?string $storeName = null
    ): void {
        if (empty($cmsPageEntity->getSpyCmsVersions())) {
            return;
        }

        $localeCmsPageDataTransfer = $this->getLocaleCmsPageDataTransfer($cmsPageEntity, $localeName);

        $cmsPageStorageEntity->setData($localeCmsPageDataTransfer->toArray());
        $cmsPageStorageEntity->setFkCmsPage($cmsPageEntity->getIdCmsPage());
        $cmsPageStorageEntity->setLocale($localeName);
        $cmsPageStorageEntity->setStore($storeName);
        $cmsPageStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $cmsPageStorageEntity->save();
    }

    /**
     * @param int[] $cmsPageIds
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage[]
     */
    protected function findCmsPageEntities(array $cmsPageIds): array
    {
        return $this->queryContainer->queryCmsPageVersionByIds($cmsPageIds)->find()->getData();
    }

    /**
     * @param int[] $cmsPageIds
     *
     * @return array
     */
    protected function findCmsStorageEntities(array $cmsPageIds): array
    {
        $cmsStorageEntities = $this->queryContainer->queryCmsPageStorageEntities($cmsPageIds)->find();
        $cmsPageStorageEntitiesByIdAndLocale = [];
        foreach ($cmsStorageEntities as $entity) {
            $cmsPageStorageEntitiesByIdAndLocale[$entity->getFkCmsPage()][$entity->getLocale()][$entity->getStore()] = $entity;
        }

        return $cmsPageStorageEntitiesByIdAndLocale;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[] $spyUrls
     * @param string $localeName
     *
     * @return string
     */
    public function extractUrlByLocales(array $spyUrls, string $localeName): string
    {
        foreach ($spyUrls as $url) {
            if ($url->getSpyLocale()->getLocaleName() === $localeName) {
                return $url->getUrl();
            }
        }

        return '';
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    protected function getLocaleCmsPageDataTransfer(SpyCmsPage $cmsPageEntity, $localeName): LocaleCmsPageDataTransfer
    {
        $url = $this->extractUrlByLocales($cmsPageEntity->getSpyUrls()
            ->getData(), $localeName);
        $cmsVersionDataTransfer = $this->cmsFacade
            ->extractCmsVersionDataTransfer($cmsPageEntity->getSpyCmsVersions()->getFirst()->getData());
        $localeCmsPageDataTransfer = $this->cmsFacade
            ->extractLocaleCmsPageDataTransfer(
                $cmsVersionDataTransfer,
                (new LocaleTransfer())->setLocaleName($localeName)
            );

        $localeCmsPageDataTransfer->setIsActive($cmsPageEntity->getIsActive());
        $localeCmsPageDataTransfer->setIdCmsPage($cmsPageEntity->getIdCmsPage());
        $localeCmsPageDataTransfer->setValidFrom($this->convertDateTimeToString($cmsPageEntity->getValidFrom()));
        $localeCmsPageDataTransfer->setValidTo($this->convertDateTimeToString($cmsPageEntity->getValidTo()));
        $localeCmsPageDataTransfer->setUrl($url);

        $expandedData = $localeCmsPageDataTransfer->toArray();
        foreach ($this->contentWidgetDataExpanderPlugins as $contentWidgetDataExpanderPlugin) {
            $expandedData = $contentWidgetDataExpanderPlugin->expand(
                $expandedData,
                (new LocaleTransfer())->setLocaleName($localeName)
            );
        }

        return (new LocaleCmsPageDataTransfer())->fromArray($expandedData);
    }

    /**
     * @param \DateTime|null $dateTime
     *
     * @return string|null
     */
    protected function convertDateTimeToString(?DateTime $dateTime = null): ?string
    {
        if (!$dateTime) {
            return null;
        }

        return $dateTime->format('c');
    }

    /**
     * @param array $cmsPageStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities($cmsPageStorageEntities): void
    {
        foreach ($cmsPageStorageEntities as $cmsPageStorageEntity) {
            foreach ($cmsPageStorageEntity as $cmsPageStorageLocaleEntity) {
                foreach ($cmsPageStorageLocaleEntity as $cmsPageStorageLocaleStoreEntity) {
                    $cmsPageStorageLocaleStoreEntity->delete();
                }
            }
        }
    }

    /**
     * @param \Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage $cmsPageStorageEntity
     *
     * @return void
     */
    protected function deleteStorageEntity(SpyCmsPageStorage $cmsPageStorageEntity): void
    {
        if ($cmsPageStorageEntity->isNew() || $cmsPageStorageEntity->getKey() === null) {
            return;
        }

        $cmsPageStorageEntity->delete();
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage[] $cmsPageEntities
     * @param array $cmsPageStorageEntities
     *
     * @return array
     */
    protected function pairCmsPageEntitiesWithCmsPageStorageEntities(
        array $cmsPageEntities,
        array $cmsPageStorageEntities
    ): array {
        $localeNameMap = $this->getLocaleNameMapByStoreName();

        $pairs = [];

        foreach ($cmsPageEntities as $cmsPageEntity) {
            [$pairs, $cmsPageStorageEntities] = $this->pairCmsPageEntityWithCmsPageStorageEntitiesByLocalesAndStores(
                $cmsPageEntity,
                $cmsPageStorageEntities,
                $pairs,
                $localeNameMap
            );
        }

        $pairs = $this->pairRemainingCmsPageStorageEntities($cmsPageStorageEntities, $pairs);

        return $pairs;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param array $cmsPageStorageEntities
     * @param array $pairs
     * @param string[][] $localeNameMap
     *
     * @return array
     */
    protected function pairCmsPageEntityWithCmsPageStorageEntitiesByLocalesAndStores(
        SpyCmsPage $cmsPageEntity,
        array $cmsPageStorageEntities,
        array $pairs,
        array $localeNameMap
    ): array {
        $idCmsPage = $cmsPageEntity->getIdCmsPage();
        $cmsPageStores = $cmsPageEntity->getSpyCmsPageStores();

        foreach ($cmsPageStores as $cmsPageStore) {
            $storeName = $cmsPageStore->getSpyStore()->getName();
            $localeNames = $localeNameMap[$storeName];

            foreach ($localeNames as $localeName) {
                $cmsPageStorageEntity = isset($cmsPageStorageEntities[$idCmsPage][$localeName][$storeName]) ?
                    $cmsPageStorageEntities[$idCmsPage][$localeName][$storeName] :
                    new SpyCmsPageStorage();

                $pairs[] = [
                    static::CMS_PAGE_ENTITY => $cmsPageEntity,
                    static::CMS_PAGE_STORAGE_ENTITY => $cmsPageStorageEntity,
                    static::LOCALE_NAME => $localeName,
                    static::STORE_NAME => $storeName,
                ];

                unset($cmsPageStorageEntities[$idCmsPage][$localeName][$storeName]);
            }
        }

        return [$pairs, $cmsPageStorageEntities];
    }

    /**
     * @param array $cmsPageStorageEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairRemainingCmsPageStorageEntities(array $cmsPageStorageEntities, array $pairs): array
    {
        array_walk_recursive($cmsPageStorageEntities, function (SpyCmsPageStorage $cmsPageStorageEntity) use (&$pairs) {
            $pairs[] = [
                static::CMS_PAGE_ENTITY => null,
                static::CMS_PAGE_STORAGE_ENTITY => $cmsPageStorageEntity,
                static::LOCALE_NAME => $cmsPageStorageEntity->getLocale(),
                static::STORE_NAME => $cmsPageStorageEntity->getStore(),
            ];
        });

        return $pairs;
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
