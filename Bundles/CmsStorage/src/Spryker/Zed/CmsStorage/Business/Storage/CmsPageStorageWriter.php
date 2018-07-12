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
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToCmsInterface;
use Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface;

class CmsPageStorageWriter implements CmsPageStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Plugin\CmsPageDataExpanderPluginInterface[]
     */
    protected $contentWidgetDataExpanderPlugins = [];
    
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToCmsInterface $cmsFacade
     * @param \Spryker\Zed\Cms\Dependency\Plugin\CmsPageDataExpanderPluginInterface[] $contentWidgetDataExpanderPlugins
     * @param \Spryker\Shared\Kernel\Store $store
     * @param bool $isSendingToQueue
     */
    public function __construct(
        CmsStorageQueryContainerInterface $queryContainer,
        CmsStorageToCmsInterface $cmsFacade,
        array $contentWidgetDataExpanderPlugins,
        Store $store,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->cmsFacade = $cmsFacade;
        $this->contentWidgetDataExpanderPlugins = $contentWidgetDataExpanderPlugins;
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
        $cmsPageStorageEntities = $this->findCmsStorageEntities($cmsPageIds);

        $this->storeData($cmsPageEntities, $cmsPageStorageEntities);
    }

    /**
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function unpublish(array $cmsPageIds)
    {
        $cmsPageStorageEntities = $this->findCmsStorageEntities($cmsPageIds);
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
     * @param \Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage|null $cmsPageStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyCmsPage $cmsPageEntity, $localeName, ?SpyCmsPageStorage $cmsPageStorageEntity = null)
    {
        if ($cmsPageStorageEntity === null) {
            $cmsPageStorageEntity = new SpyCmsPageStorage();
        }

        if (empty($cmsPageEntity->getSpyCmsVersions())) {
            return;
        }

        $localeCmsPageDataTransfer = $this->getLocalCmsPageDataTransfer($cmsPageEntity, $localeName);

        $cmsPageStorageEntity->setData($localeCmsPageDataTransfer->toArray());
        $cmsPageStorageEntity->setFkCmsPage($cmsPageEntity->getIdCmsPage());
        $cmsPageStorageEntity->setLocale($localeName);
        $cmsPageStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $cmsPageStorageEntity->save();
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
    protected function findCmsStorageEntities(array $cmsPageIds)
    {
        $spyCmsStorageEntities = $this->queryContainer->queryCmsPageStorageEntities($cmsPageIds)->find();
        $cmsPageStorageEntitiesByIdAndLocale = [];
        foreach ($spyCmsStorageEntities as $spyCmsStorageEntity) {
            $cmsPageStorageEntitiesByIdAndLocale[$spyCmsStorageEntity->getFkCmsPage()][$spyCmsStorageEntity->getLocale()] = $spyCmsStorageEntity;
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
        $localeCmsPageDataTransfer->setIdCmsPage($cmsPageEntity->getIdCmsPage());
        $localeCmsPageDataTransfer->setValidFrom($this->convertDateTimeToString($cmsPageEntity->getValidFrom()));
        $localeCmsPageDataTransfer->setValidTo($this->convertDateTimeToString($cmsPageEntity->getValidTo()));
        $localeCmsPageDataTransfer->setUrl($url);

        $expandedData = $localeCmsPageDataTransfer->toArray();
        foreach ($this->contentWidgetDataExpanderPlugins as $contentWidgetDataExpanderPlugin) {
            $expandedData = $contentWidgetDataExpanderPlugin->expand($expandedData, (new LocaleTransfer())->setLocaleName($localeName));
        }

        return (new LocaleCmsPageDataTransfer())->fromArray($expandedData);
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
