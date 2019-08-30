<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Business\Search;

use DateTime;
use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearch;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToCmsInterface;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface;
use Spryker\Zed\CmsPageSearch\Dependency\Service\CmsPageSearchToUtilEncodingInterface;
use Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainerInterface;

class CmsPageSearchWriter implements CmsPageSearchWriterInterface
{
    protected const CMS_PAGE_ENTITY = 'CMS_PAGE_ENTITY';
    protected const CMS_PAGE_SEARCH_ENTITY = 'CMS_PAGE_SEARCH_ENTITY';
    protected const LOCALE_NAME = 'LOCALE_NAME';
    protected const STORE_NAME = 'STORE_NAME';

    /**
     * @var \Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface
     */
    protected $searchFacade;

    /**
     * @var \Spryker\Zed\CmsPageSearch\Dependency\Service\CmsPageSearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToCmsInterface $cmsFacade
     * @param \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface $searchFacade
     * @param \Spryker\Zed\CmsPageSearch\Dependency\Service\CmsPageSearchToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Shared\Kernel\Store $store
     * @param bool $isSendingToQueue
     */
    public function __construct(
        CmsPageSearchQueryContainerInterface $queryContainer,
        CmsPageSearchToCmsInterface $cmsFacade,
        CmsPageSearchToSearchInterface $searchFacade,
        CmsPageSearchToUtilEncodingInterface $utilEncodingService,
        Store $store,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->cmsFacade = $cmsFacade;
        $this->searchFacade = $searchFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->store = $store;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param int[] $cmsPageIds
     *
     * @return void
     */
    public function publish(array $cmsPageIds): void
    {
        $cmsPageEntities = $this->findCmsPageEntities($cmsPageIds);
        $cmsPageSearchEntities = $this->findCmsPageSearchEntities($cmsPageIds);

        $this->storeData($cmsPageEntities, $cmsPageSearchEntities);
    }

    /**
     * @param int[] $cmsPageIds
     *
     * @return void
     */
    public function unpublish(array $cmsPageIds): void
    {
        $this->deleteSearchEntitiesByCmsPageIds($cmsPageIds);
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage[] $cmsPageEntities
     * @param \Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearch[] $cmsPageSearchEntities
     *
     * @return void
     */
    protected function storeData(array $cmsPageEntities, array $cmsPageSearchEntities): void
    {
        $pairedEntities = $this->pairCmsPageEntitiesWithCmsPageSearchEntities(
            $cmsPageEntities,
            $cmsPageSearchEntities
        );

        foreach ($pairedEntities as $pair) {
            $cmsPageEntity = $pair[static::CMS_PAGE_ENTITY];
            $cmsPageSearchEntity = $pair[static::CMS_PAGE_SEARCH_ENTITY];

            if (!$cmsPageSearchEntity->isNew() && ($cmsPageEntity === null
                    || !$cmsPageEntity->getIsActive() || !$cmsPageEntity->getIsSearchable())
            ) {
                $this->deleteSearchEntity($cmsPageSearchEntity);

                continue;
            }

            $this->storeDataSet(
                $cmsPageEntity,
                $cmsPageSearchEntity,
                $pair[static::LOCALE_NAME],
                $pair[static::STORE_NAME]
            );
        }
    }

    /**
     * @param int[] $cmsPageIds
     *
     * @return void
     */
    protected function deleteSearchEntitiesByCmsPageIds(array $cmsPageIds): void
    {
        if (empty($cmsPageIds)) {
            return;
        }

        $cmsPageSearchEntities = $this->queryContainer->queryCmsPageSearchEntities($cmsPageIds)->find();
        $this->deleteCmsPageSearchEntities($cmsPageSearchEntities);
    }

    /**
     * @param array $cmsPageSearchEntities
     *
     * @return void
     */
    protected function deleteCmsPageSearchEntities(array $cmsPageSearchEntities): void
    {
        foreach ($cmsPageSearchEntities as $cmsPageSearchEntity) {
            $this->deleteSearchEntity($cmsPageSearchEntity);
        }
    }

    /**
     * @param \Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearch $cmsPageSearchEntity
     *
     * @return void
     */
    protected function deleteSearchEntity(SpyCmsPageSearch $cmsPageSearchEntity): void
    {
        $cmsPageSearchEntity->setIsSendingToQueue($this->isSendingToQueue);
        $cmsPageSearchEntity->delete();
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param \Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearch $cmsPageSearchEntity
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return void
     */
    protected function storeDataSet(
        SpyCmsPage $cmsPageEntity,
        SpyCmsPageSearch $cmsPageSearchEntity,
        string $localeName,
        ?string $storeName = null
    ): void {
        if (empty($cmsPageEntity->getSpyCmsVersions())) {
            return;
        }

        $localeCmsPageDataTransfer = $this->getLocaleCmsPageDataTransfer($cmsPageEntity, $localeName, $storeName);
        $data = $this->mapToSearchData($localeCmsPageDataTransfer, $localeName);

        $cmsPageSearchEntity->setStructuredData($this->utilEncodingService->encodeJson($localeCmsPageDataTransfer->toArray()));
        $cmsPageSearchEntity->setData($data);
        $cmsPageSearchEntity->setFkCmsPage($cmsPageEntity->getIdCmsPage());
        $cmsPageSearchEntity->setLocale($localeName);
        $cmsPageSearchEntity->setStore($storeName);
        $cmsPageSearchEntity->setIsSendingToQueue($this->isSendingToQueue);
        $cmsPageSearchEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCmsPageDataTransfer $cmsPageDataTransfer
     * @param string $localeName
     *
     * @return array
     */
    public function mapToSearchData(LocaleCmsPageDataTransfer $cmsPageDataTransfer, string $localeName): array
    {
        return $this->searchFacade
            ->transformPageMapToDocumentByMapperName(
                $cmsPageDataTransfer->toArray(),
                (new LocaleTransfer())->setLocaleName($localeName),
                CmsPageSearchConstants::CMS_PAGE_RESOURCE_NAME
            );
    }

    /**
     * @param array $cmsPageIds
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage[]
     */
    protected function findCmsPageEntities(array $cmsPageIds): array
    {
        return $this->queryContainer->queryCmsPageVersionByIds($cmsPageIds)->find()->getData();
    }

    /**
     * @param array $cmsPageIds
     *
     * @return array
     */
    protected function findCmsPageSearchEntities(array $cmsPageIds): array
    {
        $cmsPageSearchEntities = $this->queryContainer->queryCmsPageSearchEntities($cmsPageIds)->find();
        $cmsPageStorageEntitiesByIdAndLocale = [];
        foreach ($cmsPageSearchEntities as $entity) {
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
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    protected function getLocaleCmsPageDataTransfer(
        SpyCmsPage $cmsPageEntity,
        string $localeName,
        ?string $storeName = null
    ): LocaleCmsPageDataTransfer {
        $url = $this->extractUrlByLocales(
            $cmsPageEntity->getSpyUrls()->getData(),
            $localeName
        );

        $cmsVersionDataTransfer = $this->cmsFacade->extractCmsVersionDataTransfer(
            $cmsPageEntity->getSpyCmsVersions()->getFirst()->getData()
        );

        $localeCmsPageDataTransfer = $this->cmsFacade->extractLocaleCmsPageDataTransfer(
            $cmsVersionDataTransfer,
            (new LocaleTransfer())->setLocaleName($localeName)
        );

        $localeCmsPageDataTransfer->setStoreName($storeName);
        $localeCmsPageDataTransfer->setIsActive($cmsPageEntity->getIsActive());
        $localeCmsPageDataTransfer->setIsSearchable($cmsPageEntity->getIsSearchable());
        $localeCmsPageDataTransfer->setIdCmsPage($cmsPageEntity->getIdCmsPage());
        $localeCmsPageDataTransfer->setValidFrom($this->convertDateTimeToString($cmsPageEntity->getValidFrom()));
        $localeCmsPageDataTransfer->setValidTo($this->convertDateTimeToString($cmsPageEntity->getValidTo()));
        $localeCmsPageDataTransfer->setUrl($url);

        return $localeCmsPageDataTransfer;
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
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage[] $cmsPageEntities
     * @param array $cmsPageSearchEntities
     *
     * @return array
     */
    protected function pairCmsPageEntitiesWithCmsPageSearchEntities(
        array $cmsPageEntities,
        array $cmsPageSearchEntities
    ): array {
        $localeNames = $this->store->getLocales();

        $pairs = [];

        foreach ($cmsPageEntities as $cmsPageEntity) {
            [$pairs, $cmsPageSearchEntities] = $this->pairCmsPageEntityWithCmsPageSearchEntitiesByLocalesAndStores(
                $cmsPageEntity,
                $cmsPageSearchEntities,
                $localeNames,
                $pairs
            );
        }

        $pairs = $this->pairRemainingCmsPageSearchEntities($cmsPageSearchEntities, $pairs);

        return $pairs;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param array $cmsPageSearchEntities
     * @param string[] $localeNames
     * @param array $pairs
     *
     * @return array
     */
    protected function pairCmsPageEntityWithCmsPageSearchEntitiesByLocalesAndStores(
        SpyCmsPage $cmsPageEntity,
        array $cmsPageSearchEntities,
        array $localeNames,
        array $pairs
    ): array {
        $idCmsPage = $cmsPageEntity->getIdCmsPage();
        $cmsPageStores = $cmsPageEntity->getSpyCmsPageStores();

        foreach ($localeNames as $localeName) {
            foreach ($cmsPageStores as $cmsPageStore) {
                $storeName = $cmsPageStore->getSpyStore()->getName();

                $cmsPageSearchEntity = isset($cmsPageSearchEntities[$idCmsPage][$localeName][$storeName]) ?
                    $cmsPageSearchEntities[$idCmsPage][$localeName][$storeName] :
                    new SpyCmsPageSearch();

                $pairs[] = [
                    static::CMS_PAGE_ENTITY => $cmsPageEntity,
                    static::CMS_PAGE_SEARCH_ENTITY => $cmsPageSearchEntity,
                    static::LOCALE_NAME => $localeName,
                    static::STORE_NAME => $storeName,
                ];

                unset($cmsPageSearchEntities[$idCmsPage][$localeName][$storeName]);
            }
        }

        return [$pairs, $cmsPageSearchEntities];
    }

    /**
     * @param array $cmsPageSearchEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairRemainingCmsPageSearchEntities(array $cmsPageSearchEntities, array $pairs): array
    {
        array_walk_recursive($cmsPageSearchEntities, function (SpyCmsPageSearch $cmsPageSearchEntity) use (&$pairs) {
            $pairs[] = [
                static::CMS_PAGE_ENTITY => null,
                static::CMS_PAGE_SEARCH_ENTITY => $cmsPageSearchEntity,
                static::LOCALE_NAME => $cmsPageSearchEntity->getLocale(),
                static::STORE_NAME => $cmsPageSearchEntity->getStore(),
            ];
        });

        return $pairs;
    }
}
