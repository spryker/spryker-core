<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener;

use DateTime;
use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearch;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsPageSearch\Communication\CmsPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainerInterface getQueryContainer()
 */
class AbstractCmsPageSearchListener extends AbstractPlugin
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
        $cmsPageStorageEntities = $this->findCmsPageSearchEntities($cmsPageIds);

        $this->storeData($cmsPageEntities, $cmsPageStorageEntities);
    }

    /**
     * @param array $cmsPageIds
     *
     * @return void
     */
    protected function unpublish(array $cmsPageIds)
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

        $cmsPageStorageEntity->setStructuredData($this->getFactory()->getUtilEncoding()->encodeJson($localeCmsPageDataTransfer->toArray()));
        $cmsPageStorageEntity->setData($data);
        $cmsPageStorageEntity->setFkCmsPage($cmsPageEntity->getIdCmsPage());
        $cmsPageStorageEntity->setStore($this->getStore()->getStoreName());
        $cmsPageStorageEntity->setLocale($localeName);
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
        return $this->getFactory()->getSearchFacade()
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
        return $this->getQueryContainer()->queryCmsPageVersionByIds($cmsPageIds)->find()->getData();
    }

    /**
     * @param array $cmsPageIds
     *
     * @return array
     */
    protected function findCmsPageSearchEntities(array $cmsPageIds)
    {
        $spyCmsPageSearchEntities = $this->getQueryContainer()->queryCmsPageSearchEntities($cmsPageIds)->find();
        $cmsPageStorageEntitiesByIdAndLocale = [];
        foreach ($spyCmsPageSearchEntities as $spyCmsPageSearchEntity) {
            $cmsPageStorageEntitiesByIdAndLocale[$spyCmsPageSearchEntity->getFkCmsPage()][$spyCmsPageSearchEntity->getLocale()] = $spyCmsPageSearchEntity;
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
