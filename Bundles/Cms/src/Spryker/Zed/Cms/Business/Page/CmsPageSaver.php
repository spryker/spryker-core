<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Exception;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface;
use Spryker\Zed\Cms\Business\Template\TemplateManagerInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Throwable;

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
     * @var \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface
     */
    protected $cmsPageUrlBuilder;

    /**
     * @var \Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface
     */
    protected $cmsGlossarySaver;

    /**
     * @var \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface
     */
    protected $templateManager;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface $urlFacade
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface $touchFacade
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface $cmsPageUrlBuilder
     * @param \Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface $cmsGlossarySaver
     * @param \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface $templateManager
     */
    public function __construct(
        CmsToUrlInterface $urlFacade,
        CmsToTouchInterface $touchFacade,
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsPageUrlBuilderInterface $cmsPageUrlBuilder,
        CmsGlossarySaverInterface $cmsGlossarySaver,
        TemplateManagerInterface $templateManager
    ) {
        $this->urlFacade = $urlFacade;
        $this->touchFacade = $touchFacade;
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsPageUrlBuilder = $cmsPageUrlBuilder;
        $this->cmsGlossarySaver = $cmsGlossarySaver;
        $this->templateManager = $templateManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return int
     */
    public function createPage(CmsPageTransfer $cmsPageTransfer)
    {
        try {
            $cmsPageTransfer->requirePageAttributes();
            $this->checkTemplateFileExists($cmsPageTransfer->getFkTemplate());

            $this->cmsQueryContainer->getConnection()->beginTransaction();

            $cmsPageEntity = $this->createCmsPageEntity();
            $cmsPageEntity = $this->mapCmsPageEntity($cmsPageTransfer, $cmsPageEntity);
            $cmsPageEntity->save();

            $localizedAttributeEntities = [];
            foreach ($cmsPageTransfer->getPageAttributes() as $cmsPageAttributesTransfer) {
                $cmsPageLocalizedAttributesEntity = $this->createLocalizedAttributes($cmsPageAttributesTransfer, $cmsPageEntity);
                $localizedAttributeEntities[$cmsPageAttributesTransfer->getFkLocale()] = $cmsPageLocalizedAttributesEntity;
            }

            $this->saveCmsPageLocalizedMetaAttributes($cmsPageTransfer, $localizedAttributeEntities);

            $this->cmsQueryContainer->getConnection()->commit();
        } catch (Exception $exception) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $exception;
        } catch (Throwable $exception) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        return $cmsPageEntity->getIdCmsPage();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @throws \Exception
     * @throws \Throwable
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function updatePage(CmsPageTransfer $cmsPageTransfer)
    {
        $cmsPageEntity = $this->getCmsPageEntity($cmsPageTransfer);
        $this->checkTemplateFileExists($cmsPageTransfer->getFkTemplate());

        if ($cmsPageEntity === null) {
            throw new MissingPageException(
                sprintf(
                    'CMS page with id "%d" was not found',
                    $cmsPageTransfer->getFkPage()
                )
            );
        }

        try {
            $this->cmsQueryContainer->getConnection()->beginTransaction();

            if ($cmsPageEntity->getFkTemplate() !== $cmsPageTransfer->getFkTemplate()) {
                $this->cmsGlossarySaver->deleteCmsGlossary($cmsPageEntity->getIdCmsPage());
            }

            $cmsPageEntity = $this->mapCmsPageEntity($cmsPageTransfer, $cmsPageEntity);
            $cmsPageEntity->save();

            $cmsPageLocalizedAttributesList = $this->createCmsPageLocalizedAttributesList($cmsPageEntity);
            $this->updateCmsPageLocalizedAttributes($cmsPageTransfer, $cmsPageLocalizedAttributesList, $cmsPageEntity);
            $this->updateCmsPageLocalizedMetaAttributes($cmsPageTransfer, $cmsPageLocalizedAttributesList);

            if ($cmsPageEntity->getIsActive()) {
                $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $cmsPageEntity->getIdCmsPage());
            }

            $this->cmsQueryContainer->getConnection()->commit();
        } catch (Exception $exception) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $exception;
        } catch (Throwable $exception) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        return $cmsPageTransfer;
    }

    /**
     * @param int $idTemplate
     *
     * @return void
     */
    protected function checkTemplateFileExists($idTemplate)
    {
        $templateTransfer = $this->templateManager
            ->getTemplateById($idTemplate);

        $this->templateManager
            ->checkTemplateFileExists($templateTransfer->getTemplatePath());
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer, $idCmsPage)
    {
        $url = $this->cmsPageUrlBuilder->buildPageUrl($cmsPageAttributesTransfer);

        $urlTransfer = $this->createUrlTransfer($cmsPageAttributesTransfer, $idCmsPage, $url);

        return $this->urlFacade->createUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function mapCmsPageEntity(CmsPageTransfer $cmsPageTransfer, SpyCmsPage $cmsPageEntity)
    {
        $cmsPageEntity->fromArray($cmsPageTransfer->toArray());

        return $cmsPageEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return void
     */
    protected function updatePageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer, SpyUrl $urlEntity)
    {
        $url = $this->cmsPageUrlBuilder->buildPageUrl($cmsPageAttributesTransfer);

        if ($urlEntity->getUrl() !== $url) {
            $urlTransfer = $this->createUrlTransfer(
                $cmsPageAttributesTransfer,
                $cmsPageAttributesTransfer->getIdCmsPage(),
                $url
            );
            $urlTransfer->setIdUrl($urlEntity->getIdUrl());
            $this->urlFacade->updateUrl($urlTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|null
     */
    protected function getCmsPageEntity(CmsPageTransfer $cmsPageTransfer)
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageById($cmsPageTransfer->getFkPage())
            ->findOne();

        return $cmsPageEntity;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
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
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes[]
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
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function mapCmsPageLocalizedAttributes(
        SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity,
        CmsPageAttributesTransfer $cmsPageAttributesTransfer
    ) {
        $cmsPageLocalizedAttributesEntity->fromArray($cmsPageAttributesTransfer->toArray());

        return $cmsPageLocalizedAttributesEntity;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer $cmsPageMetaAttributesTransfer
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function mapCmsPageLocalizedMetaAttributes(
        SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity,
        CmsPageMetaAttributesTransfer $cmsPageMetaAttributesTransfer
    ) {
        $cmsPageLocalizedAttributesEntity->fromArray($cmsPageMetaAttributesTransfer->modifiedToArray());

        return $cmsPageLocalizedAttributesEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function createLocalizedAttributes(CmsPageAttributesTransfer $cmsPageAttributesTransfer, SpyCmsPage $cmsPageEntity)
    {
        $cmsPageLocalizedAttributesEntity = $this->createCmsPageLocalizedAttributesEntity();
        $cmsPageLocalizedAttributesEntity = $this->mapCmsPageLocalizedAttributes($cmsPageLocalizedAttributesEntity, $cmsPageAttributesTransfer);
        $cmsPageLocalizedAttributesEntity->setFkCmsPage($cmsPageEntity->getIdCmsPage());

        $cmsPageLocalizedAttributesEntity->save();

        $cmsPageAttributesTransfer->setIdCmsPageLocalizedAttributes(
            $cmsPageLocalizedAttributesEntity->getIdCmsPageLocalizedAttributes()
        );

        $this->createPageUrl($cmsPageAttributesTransfer, $cmsPageEntity->getIdCmsPage());

        return $cmsPageLocalizedAttributesEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes[] $cmsPageLocalizedAttributesList
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
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes[] $localizedAttributeEntities
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

            $cmsPageMetaAttributesTransfer->setIdCmsPageLocalizedAttributes(
                $cmsPageLocalizedAttributesEntity->getIdCmsPageLocalizedAttributes()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes[] $cmsPageLocalizedAttributesList
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

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     * @param int $idCmsPage
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(CmsPageAttributesTransfer $cmsPageAttributesTransfer, $idCmsPage, $url)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);
        $urlTransfer->setFkLocale($cmsPageAttributesTransfer->getFkLocale());
        $urlTransfer->setFkResourcePage($idCmsPage);

        return $urlTransfer;
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function createCmsPageEntity()
    {
        return new SpyCmsPage();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function createCmsPageLocalizedAttributesEntity()
    {
        return new SpyCmsPageLocalizedAttributes();
    }
}
