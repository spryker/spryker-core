<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener;

use DateTime;
use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsStorage\Communication\CmsStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface getQueryContainer()
 */
class AbstractCmsPageStorageListener extends AbstractPlugin
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $cmsPageIds
     *
     * @return void
     */
    protected function publish(array $cmsPageIds)
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
    protected function unpublish(array $cmsPageIds)
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
        $localeNames = $this->getStore()->getLocales();

        foreach ($cmsPageEntities as $cmsPageEntity) {
            foreach ($localeNames as $localeName) {
                $idCmsPage = $cmsPageEntity->getIdCmsPage();
                if (isset($cmsPageStorageEntities[$idCmsPage][$localeName])) {
                    $this->storeDataSet($cmsPageEntity, $localeName, $cmsPageStorageEntities[$idCmsPage][$localeName]);
                } else {
                    $this->storeDataSet($cmsPageEntity, $localeName);
                }
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
        $cmsPageStorageEntity->setStore($this->getStore()->getStoreName());
        $cmsPageStorageEntity->setLocale($localeName);
        $cmsPageStorageEntity->save();
    }

    /**
     * @param array $cmsPageIds
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage[]
     */
    protected function findCmsPageEntities(array $cmsPageIds)
    {
        return $this->getQueryContainer()->queryCmsPageVersionByIds($cmsPageIds)->find()->getData();
    }

    /**
     * @param array $cmsPageIds
     *
     * @return array
     */
    protected function findCmsStorageEntities(array $cmsPageIds)
    {
        $spyCmsStorageEntities = $this->getQueryContainer()->queryCmsPageStorageEntities($cmsPageIds)->find();
        $cmsPageStorageEntitiesByIdAndLocale = [];
        foreach ($spyCmsStorageEntities as $spyCmsStorageEntity) {
            $cmsPageStorageEntitiesByIdAndLocale[$spyCmsStorageEntity->getFkCmsPage()][$spyCmsStorageEntity->getLocale()] = $spyCmsStorageEntity;
        }

        return $cmsPageStorageEntitiesByIdAndLocale;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getFactory()->getStore();
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
        $cmsVersionDataTransfer = $this->getFactory()
            ->getCmsFacade()
            ->extractCmsVersionDataTransfer($cmsPageEntity->getSpyCmsVersions()->getFirst()->getData());
        $localeCmsPageDataTransfer = $this->getFactory()
            ->getCmsFacade()
            ->extractLocaleCmsPageDataTransfer($cmsVersionDataTransfer, (new LocaleTransfer())->setLocaleName($localeName));

        $localeCmsPageDataTransfer->setIsActive($cmsPageEntity->getIsActive());
        $localeCmsPageDataTransfer->setIdCmsPage($cmsPageEntity->getIdCmsPage());
        $localeCmsPageDataTransfer->setValidFrom($this->convertDateTimeToString($cmsPageEntity->getValidFrom()));
        $localeCmsPageDataTransfer->setValidTo($this->convertDateTimeToString($cmsPageEntity->getValidTo()));
        $localeCmsPageDataTransfer->setUrl($url);

        $expandedData = [];
        foreach ($this->getFactory()->getContentWidgetDataExpanderPlugins() as $contentWidgetDataExpanderPlugin) {
            $expandedData = $contentWidgetDataExpanderPlugin->expand($localeCmsPageDataTransfer->toArray(), (new LocaleTransfer())->setLocaleName($localeName));
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
