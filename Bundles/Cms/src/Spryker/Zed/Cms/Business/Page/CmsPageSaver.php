<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageSaver implements CmsPageSaverInterface
{

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface $urlFacade
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface $touchFacade
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(
        CmsToUrlInterface $urlFacade,
        CmsToTouchInterface $touchFacade,
        CmsQueryContainerInterface $cmsQueryContainer
    ) {
        $this->urlFacade = $urlFacade;
        $this->touchFacade = $touchFacade;
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return int
     */
    public function createPage(CmsPageTransfer $cmsPageTransfer)
    {
        $cmsPageTransfer->requirePageAttributes();

        $this->cmsQueryContainer->getConnection()->beginTransaction();

        $cmsPageEntity = new SpyCmsPage();
        $cmsPageEntity = $this->mapCmsPageEntity($cmsPageTransfer, $cmsPageEntity);
        $cmsPageEntity->save();

        $localizedAttributeEntities = [];
        foreach ($cmsPageTransfer->getPageAttributes() as $cmsPageAttributesTransfer) {
             $cmsPageLocalizedAttributesEntity = $this->createLocalizedAttributes($cmsPageAttributesTransfer, $cmsPageEntity);
             $localizedAttributeEntities[$cmsPageAttributesTransfer->getFkLocale()] = $cmsPageLocalizedAttributesEntity;
        }

        $this->saveCmsPageLocalizedMetaAttributes($cmsPageTransfer, $localizedAttributeEntities);

        $this->cmsQueryContainer->getConnection()->commit();

        return $cmsPageEntity->getIdCmsPage();

    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function updatePage(CmsPageTransfer $cmsPageTransfer)
    {
        $cmsPageEntity = $this->getCmsPageEntity($cmsPageTransfer);

        $this->cmsQueryContainer->getConnection()->beginTransaction();

        $cmsPageEntity = $this->mapCmsPageEntity($cmsPageTransfer, $cmsPageEntity);
        $cmsPageEntity->save();

        $cmsPageLocalizedAttributesList = $this->createCmsPageLocalizedAttributesList($cmsPageEntity);
        $this->updateCmsPageLocalizedAttributes($cmsPageTransfer, $cmsPageLocalizedAttributesList, $cmsPageEntity);
        $this->updateCmsPageLocalizedMetaAttributes($cmsPageTransfer, $cmsPageLocalizedAttributesList);

        if ($cmsPageEntity->getIsActive()) {
            $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $cmsPageEntity->getIdCmsPage());
        }

        $this->cmsQueryContainer->getConnection()->commit();

        return $cmsPageTransfer;
    }


    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer, $idCmsPage)
    {
        $url = $this->buildPageUrl(
            $cmsPageAttributesTransfer->getUrl(),
            $cmsPageAttributesTransfer->getLocaleName()
        );

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setIdLocale($cmsPageAttributesTransfer->getFkLocale());

        return $this->urlFacade->createUrl(
            $url,
            $localeTransfer,
            CmsConstants::RESOURCE_TYPE_PAGE,
            $idCmsPage
        );
    }

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return string
     */
    protected function buildPageUrl($url, $localeName)
    {
        $languageCode = $this->extractLanguageCode($localeName);

        if (preg_match('#^/' . $languageCode . '/#i', $url) > 0) {
            return $url;
        }

        $url = preg_replace('#^/#', '', $url);

        $urlWithLanguageCode =  '/' . $languageCode . '/' . $url;

        return $urlWithLanguageCode;
    }

    /**
     * @param string $localeName
     *
     * @return string
     */
    protected function extractLanguageCode($localeName)
    {
        $localeNameParts = explode('_', $localeName);
        $languageCode = $localeNameParts[0];

        return $languageCode;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function mapCmsPageEntity(CmsPageTransfer $cmsPageTransfer, SpyCmsPage $cmsPageEntity)
    {
        $cmsPageEntity->fromArray($cmsPageTransfer->toArray());

        return $cmsPageEntity;
    }

    /**
     * @param CmsPageAttributesTransfer $cmsPageAttributesTransfer
     * @param SpyUrl $urlEntity
     *
     * @return void
     */
    protected function updatePageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer, SpyUrl $urlEntity)
    {
        $url = $this->buildPageUrl(
            $cmsPageAttributesTransfer->getUrl(),
            $cmsPageAttributesTransfer->getLocaleName()
        );

        if ($urlEntity->getUrl() !== $url) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setIdUrl($urlEntity->getIdUrl());
            $urlTransfer->fromArray($cmsPageAttributesTransfer->toArray(), true);
            $urlTransfer->setUrl($url);

            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function getCmsPageEntity(CmsPageTransfer $cmsPageTransfer)
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageById($cmsPageTransfer->getFkPage())
            ->findOne();

        if ($cmsPageEntity === null) {
            throw new MissingPageException(
                sprintf(
                    'CMS page with id "%d" was not found',
                    $cmsPageTransfer->getFkPage()
                )
            );
        }

        return $cmsPageEntity;
    }

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return array
     */
    protected function createCmsPageList(SpyCmsPage $cmsPageEntity)
    {
        $cmsPageUrlList = [];
        foreach ($cmsPageEntity->getSpyUrls() as $urlEntity) {
            $cmsPageUrlList[$urlEntity->getFkLocale()] = $urlEntity;
        }
        return $cmsPageUrlList;
    }

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return array
     */
    protected function createCmsPageLocalizedAttributesList(SpyCmsPage $cmsPageEntity)
    {
        $cmsPageLocalizedAttributesList = [];
        foreach ($cmsPageEntity->getSpyCmsPageLocalizedAttributess() as $cmsPageLocalizedAttributesEntity) {
            $cmsPageLocalizedAttributesList[$cmsPageLocalizedAttributesEntity->getIdCmsPageLocalizedAttributes()] = $cmsPageLocalizedAttributesEntity;
        }
        return $cmsPageLocalizedAttributesList;
    }

    /**
     * @param SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     * @param CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return SpyCmsPageLocalizedAttributes
     */
    protected function mapCmsPageLocalizedAttributes(
        SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity,
        CmsPageAttributesTransfer $cmsPageAttributesTransfer
    ) {
        $cmsPageLocalizedAttributesEntity->fromArray($cmsPageAttributesTransfer->toArray());

        return $cmsPageLocalizedAttributesEntity;
    }

    /**
     * @param SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     * @param CmsPageMetaAttributesTransfer $cmsPageMetaAttributesTransfer
     *
     * @return SpyCmsPageLocalizedAttributes
     */
    protected function mapCmsPageLocalizedMetaAttributes(
        SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity,
        CmsPageMetaAttributesTransfer $cmsPageMetaAttributesTransfer
    ) {
        $cmsPageLocalizedAttributesEntity->fromArray($cmsPageMetaAttributesTransfer->toArray());

        return $cmsPageLocalizedAttributesEntity;
    }

    /**
     * @param CmsPageAttributesTransfer $cmsPageAttributesTransfer
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function createLocalizedAttributes(CmsPageAttributesTransfer $cmsPageAttributesTransfer, SpyCmsPage $cmsPageEntity)
    {
        $cmsPageLocalizedAttributesEntity = new SpyCmsPageLocalizedAttributes();
        $cmsPageLocalizedAttributesEntity = $this->mapCmsPageLocalizedAttributes($cmsPageLocalizedAttributesEntity, $cmsPageAttributesTransfer);
        $cmsPageLocalizedAttributesEntity->setFkCmsPage($cmsPageEntity->getIdCmsPage());

        $this->createPageUrl($cmsPageAttributesTransfer, $cmsPageEntity->getIdCmsPage());

        return $cmsPageLocalizedAttributesEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param array|CmsPageLocalizedAttributesTransfer[] $cmsPageLocalizedAttributesList
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return void
     */
    protected function updateCmsPageLocalizedAttributes(
        CmsPageTransfer $cmsPageTransfer,
        array $cmsPageLocalizedAttributesList,
        SpyCmsPage $cmsPageEntity
    ) {
        $cmsPageUrlList = $this->createCmsPageList($cmsPageEntity);

        foreach ($cmsPageTransfer->getPageAttributes() as $cmsPageAttributesTransfer) {

            $cmsPageLocalizedAttributesEntity = $cmsPageLocalizedAttributesList[$cmsPageAttributesTransfer->getIdCmsPageLocalizedAttributes()];
            $urlEntity = $cmsPageUrlList[$cmsPageAttributesTransfer->getFkLocale()];

            $cmsPageLocalizedAttributesEntity = $this->mapCmsPageLocalizedAttributes($cmsPageLocalizedAttributesEntity, $cmsPageAttributesTransfer);
            $this->updatePageUrl($cmsPageAttributesTransfer, $urlEntity);

            $cmsPageLocalizedAttributesEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param array|SpyCmsPageLocalizedAttributes[] $localizedAttributeEntities
     *
     * @return void
     */
    protected function saveCmsPageLocalizedMetaAttributes(CmsPageTransfer $cmsPageTransfer, array $localizedAttributeEntities)
    {
        foreach ($cmsPageTransfer->getMetaAttributes() as $cmsPageMetaAttributesTransfer) {
            $cmsPageLocalizedAttributesEntity = $localizedAttributeEntities[$cmsPageMetaAttributesTransfer->getFkLocale()];
            $cmsPageLocalizedAttributesEntity = $this->mapCmsPageLocalizedMetaAttributes(
                $cmsPageLocalizedAttributesEntity,
                $cmsPageMetaAttributesTransfer
            );
            $cmsPageLocalizedAttributesEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param array|SpyCmsPageLocalizedAttributes[] $cmsPageLocalizedAttributesList
     *
     * @return void
     */
    protected function updateCmsPageLocalizedMetaAttributes(
        CmsPageTransfer $cmsPageTransfer,
        array $cmsPageLocalizedAttributesList
    ) {
        foreach ($cmsPageTransfer->getMetaAttributes() as $cmsPageMetaAttributesTransfer) {

            $cmsPageLocalizedAttributesEntity = $cmsPageLocalizedAttributesList[$cmsPageMetaAttributesTransfer->getIdCmsPageLocalizedAttributes()];

            $cmsPageLocalizedAttributesEntity = $this->mapCmsPageLocalizedMetaAttributes(
                $cmsPageLocalizedAttributesEntity,
                $cmsPageMetaAttributesTransfer
            );

            $cmsPageLocalizedAttributesEntity->save();
        }
    }
}
