<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\CmsPageDataTransfer;
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
                    $this->storeDataSet($cmsPageEntity, $cmsPageStorageEntities[$idCmsPage][$localeName], $localeName);
                } else {
                    $this->storeDataSet($cmsPageEntity, null, $localeName);
                }
            }
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param \Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage|null $cmsPageStorageEntity
     * @param string $localeName
     *
     * @return void
     */
    protected function storeDataSet(SpyCmsPage $cmsPageEntity, SpyCmsPageStorage $cmsPageStorageEntity, $localeName)
    {
        if ($cmsPageStorageEntity === null) {
            $cmsPageStorageEntity = new SpyCmsPageStorage();
        }

        if (empty($cmsPageEntity->getSpyCmsVersions())) {
            return;
        }

        $url = $this->extractUrlByLocales($cmsPageEntity->getSpyUrls()->getData(), $localeName);
        $cmsPageDataTransfer = new CmsPageDataTransfer();
        $cmsPageDataTransfer->setIsActive($cmsPageEntity->getIsActive());
        $cmsPageDataTransfer->setIdCmsPage($cmsPageEntity->getIdCmsPage());
        $cmsPageDataTransfer->setValidFrom($cmsPageEntity->getValidFrom());
        $cmsPageDataTransfer->setValidTo($cmsPageEntity->getValidTo());
        $cmsPageDataTransfer->setUrl($url);
        $cmsPageDataTransfer = $this->getFactory()->getCmsFacade()->expandCmsPageDataTransfer($cmsPageDataTransfer, $cmsPageEntity->getSpyCmsVersions()->getFirst()->getData(), $localeName);

        $cmsPageStorageEntity->setData($cmsPageDataTransfer->toArray());
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
     * @param $localeName
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

}
