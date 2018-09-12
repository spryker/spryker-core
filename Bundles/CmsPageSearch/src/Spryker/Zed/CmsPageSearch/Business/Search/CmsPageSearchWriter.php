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
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function publish(array $cmsPageIds)
    {
        $cmsPageEntities = $this->findCmsPageEntities($cmsPageIds);
        $cmsPageStorageEntities = $this->findCmsPageSearchEntities($cmsPageIds);

        $this->storeData($cmsPageEntities, $cmsPageStorageEntities);
    }

    /**
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function unpublish(array $cmsPageIds)
    {
        $cmsPageStorageEntities = $this->findCmsPageSearchEntities($cmsPageIds);
        foreach ($cmsPageStorageEntities as $cmsPageStorageEntity) {
            $cmsPageStorageEntity->delete();
        }
    }

    /**
     * @param array $cmsPageEntities
     * @param array $cmsPageStorageEntities
     *
     * @return void
     */
    protected function storeData(array $cmsPageEntities, array $cmsPageStorageEntities)
    {
        $localeNames = $this->store->getLocales();

        foreach ($cmsPageEntities as $cmsPageEntity) {
            foreach ($localeNames as $localeName) {
                $idCmsPage = $cmsPageEntity->getIdCmsPage();
                if (isset($cmsPageStorageEntities[$idCmsPage][$localeName])) {
                    $this->storeDataSet($cmsPageEntity, $localeName, $cmsPageStorageEntities[$idCmsPage][$localeName]);

                    continue;
                }

                $this->storeDataSet($cmsPageEntity, $localeName);
            }
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param string $localeName
     * @param \Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearch|null $cmsPageStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyCmsPage $cmsPageEntity, $localeName, ?SpyCmsPageSearch $cmsPageStorageEntity = null)
    {
        if ($cmsPageStorageEntity === null) {
            $cmsPageStorageEntity = new SpyCmsPageSearch();
        }

        if (empty($cmsPageEntity->getSpyCmsVersions())) {
            return;
        }

        $localeCmsPageDataTransfer = $this->getLocalCmsPageDataTransfer($cmsPageEntity, $localeName);
        $data = $this->mapToSearchData($localeCmsPageDataTransfer, $localeName);

        $cmsPageStorageEntity->setStructuredData($this->utilEncodingService->encodeJson($localeCmsPageDataTransfer->toArray()));
        $cmsPageStorageEntity->setData($data);
        $cmsPageStorageEntity->setFkCmsPage($cmsPageEntity->getIdCmsPage());
        $cmsPageStorageEntity->setLocale($localeName);
        $cmsPageStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $cmsPageStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCmsPageDataTransfer $cmsPageDataTransfer
     * @param string $localeName
     *
     * @return array
     */
    public function mapToSearchData(LocaleCmsPageDataTransfer $cmsPageDataTransfer, $localeName)
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
    protected function findCmsPageEntities(array $cmsPageIds)
    {
        return $this->queryContainer->queryCmsPageVersionByIds($cmsPageIds)->find()->getData();
    }

    /**
     * @param array $cmsPageIds
     *
     * @return array
     */
    protected function findCmsPageSearchEntities(array $cmsPageIds)
    {
        $spyCmsPageSearchEntities = $this->queryContainer->queryCmsPageSearchEntities($cmsPageIds)->find();
        $cmsPageStorageEntitiesByIdAndLocale = [];
        foreach ($spyCmsPageSearchEntities as $spyCmsPageSearchEntity) {
            $cmsPageStorageEntitiesByIdAndLocale[$spyCmsPageSearchEntity->getFkCmsPage()][$spyCmsPageSearchEntity->getLocale()] = $spyCmsPageSearchEntity;
        }

        return $cmsPageStorageEntitiesByIdAndLocale;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[] $spyUrls
     * @param string $localeName
     *
     * @return string
     */
    public function extractUrlByLocales(array $spyUrls, $localeName)
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
    protected function getLocalCmsPageDataTransfer(SpyCmsPage $cmsPageEntity, $localeName)
    {
        $url = $this->extractUrlByLocales($cmsPageEntity->getSpyUrls()
            ->getData(), $localeName);
        $cmsVersionDataTransfer = $this->cmsFacade
            ->extractCmsVersionDataTransfer($cmsPageEntity->getSpyCmsVersions()->getFirst()->getData());
        $localeCmsPageDataTransfer = $this->cmsFacade
            ->extractLocaleCmsPageDataTransfer($cmsVersionDataTransfer, (new LocaleTransfer())->setLocaleName($localeName));

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
     * @return null|string
     */
    protected function convertDateTimeToString(?DateTime $dateTime = null)
    {
        if (!$dateTime) {
            return null;
        }

        return $dateTime->format('c');
    }
}
