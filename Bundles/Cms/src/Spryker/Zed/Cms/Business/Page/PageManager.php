<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use ArrayObject;
use Generated\Shared\Transfer\CmsPageLocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Business\Exception\LocaleNotFoundException;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Exception\MissingTemplateException;
use Spryker\Zed\Cms\Business\Template\TemplateManagerInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class PageManager implements PageManagerInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface
     */
    protected $templateManager;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface $templateManager
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlFacadeInterface $urlFacade
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer, TemplateManagerInterface $templateManager, CmsToGlossaryFacadeInterface $glossaryFacade, CmsToTouchFacadeInterface $touchFacade, CmsToUrlFacadeInterface $urlFacade)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->templateManager = $templateManager;
        $this->glossaryFacade = $glossaryFacade;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePage(PageTransfer $pageTransfer): PageTransfer
    {
        $this->checkTemplateExists($pageTransfer->getFkTemplate());

        if ($pageTransfer->getIdCmsPage() === null) {
            return $this->createPage($pageTransfer);
        }

        return $this->updatePage($pageTransfer);
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    protected function createPage(PageTransfer $pageTransfer): PageTransfer
    {
        $this->checkTemplateExists($pageTransfer->getFkTemplate());

        $this->cmsQueryContainer
            ->getConnection()
            ->beginTransaction();

        $this->persistNewPage($pageTransfer);

        $this->cmsQueryContainer
            ->getConnection()
            ->commit();

        return $pageTransfer;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return void
     */
    protected function persistNewPage(PageTransfer $pageTransfer): void
    {
        $pageEntity = new SpyCmsPage();
        $pageEntity->fromArray($pageTransfer->toArray());
        $pageEntity->save();

        $pageTransfer->setIdCmsPage($pageEntity->getIdCmsPage());

        if ($pageTransfer->getUrl() !== null) {
            $urlTransfer = $this->createPageUrl($pageTransfer);
            $pageTransfer->setUrl($urlTransfer);
        }

        $this->createCmsPageLocalizedAttributes($pageTransfer->getLocalizedAttributes(), $pageEntity);
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    protected function updatePage(PageTransfer $pageTransfer): PageTransfer
    {
        $this->cmsQueryContainer
            ->getConnection()
            ->beginTransaction();

        $this->persistExistingPage($pageTransfer);

        $this->cmsQueryContainer
            ->getConnection()
            ->commit();

        return $pageTransfer;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return void
     */
    protected function persistExistingPage(PageTransfer $pageTransfer): void
    {
        $pageEntity = $this->getPageById($pageTransfer->getIdCmsPage());
        $pageEntity->fromArray($pageTransfer->modifiedToArray());

        if ($pageTransfer->getUrl() !== null) {
            $urlTransfer = $this->updatePageUrl($pageTransfer);
            $pageTransfer->setUrl($urlTransfer);
        }

        $pageEntity->save();

        $this->updateCmsPageLocalizedAttributes($pageTransfer->getLocalizedAttributes(), $pageEntity);
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param int $idTemplate
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     *
     * @return void
     */
    protected function checkTemplateExists(int $idTemplate): void
    {
        if (!$this->templateManager->hasTemplateId($idTemplate)) {
            throw new MissingTemplateException(sprintf('Tried to save page referring to a missing template with id %s', $idTemplate));
        }
    }

    /**
     * @param int $idPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return void
     */
    protected function checkPageExists(int $idPage): void
    {
        if (!$this->hasPageId($idPage)) {
            throw new MissingPageException(sprintf('Tried to refer to a missing page with id %s', $idPage));
        }
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param int $idPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    public function getPageById(int $idPage): SpyCmsPage
    {
        $page = $this->cmsQueryContainer->queryPageById($idPage)
            ->findOne();
        if (!$page) {
            throw new MissingPageException(sprintf('Tried to retrieve a missing page with id %s', $idPage));
        }

        return $page;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $pageEntity
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function convertPageEntityToTransfer(SpyCmsPage $pageEntity): PageTransfer
    {
        $pageTransfer = new PageTransfer();
        $pageTransfer->fromArray($pageEntity->toArray());

        return $pageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function touchPageActive(PageTransfer $pageTransfer, ?LocaleTransfer $localeTransfer = null): void
    {
        $pageMappings = $this->cmsQueryContainer->queryGlossaryKeyMappingsByPageId($pageTransfer->getIdCmsPage())
            ->find();
        foreach ($pageMappings as $pageMapping) {
            $this->glossaryFacade->touchTranslationForKeyId($pageMapping->getFkGlossaryKey(), $localeTransfer);
        }

        $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $pageTransfer->getIdCmsPage(), false);
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrl(PageTransfer $pageTransfer): UrlTransfer
    {
        $this->checkPageExists($pageTransfer->getIdCmsPage());
        $idLocale = $pageTransfer->getUrl()->getFkLocale();

        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl($pageTransfer->getUrl()->getUrl())
            ->setFkLocale($idLocale)
            ->setFkResourcePage($pageTransfer->getIdCmsPage());

        return $this->urlFacade->createUrl($urlTransfer);
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function updatePageUrl(PageTransfer $pageTransfer): UrlTransfer
    {
        $this->checkPageExists($pageTransfer->getIdCmsPage());

        return $this->urlFacade->updateUrl($pageTransfer->getUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrlWithLocale(PageTransfer $pageTransfer, string $url, LocaleTransfer $localeTransfer): UrlTransfer
    {
        $this->checkPageExists($pageTransfer->getIdCmsPage());

        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl($url)
            ->setFkLocale($localeTransfer->requireIdLocale()->getIdLocale())
            ->setFkResourcePage($pageTransfer->getIdCmsPage());

        return $this->urlFacade->createUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $pageTransfer): UrlTransfer
    {
        $this->cmsQueryContainer->getConnection()->beginTransaction();

        if (!$this->hasPageId($pageTransfer->getIdCmsPage())) {
            $pageTransfer = $this->savePage($pageTransfer);
        }

        $urlTransfer = $pageTransfer->getUrl();
        if (!$this->urlFacade->hasUrlCaseInsensitive($urlTransfer)) {
            $urlTransfer = $this->createPageUrl($pageTransfer);
            $pageTransfer->setUrl($urlTransfer);
        }

        $this->cmsQueryContainer->getConnection()->commit();

        return $urlTransfer;
    }

    /**
     * @param int $idPage
     *
     * @return bool
     */
    protected function hasPageId(int $idPage): bool
    {
        $query = $this->cmsQueryContainer->queryPageById($idPage);

        return $query->count() > 0;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\LocaleNotFoundException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer($idLocale): LocaleTransfer
    {
        $localeEntity = $this->cmsQueryContainer->queryLocaleById($idLocale)->findOne();

        if ($localeEntity === null) {
            throw new LocaleNotFoundException(
                sprintf('Locale with id %s not found', $idLocale)
            );
        }

        $localTransfer = new LocaleTransfer();
        $localTransfer->fromArray($localeEntity->toArray());

        return $localTransfer;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\CmsPageLocalizedAttributesTransfer[]|\ArrayObject $cmsPageLocalizedAttributesTransfers
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $pageEntity
     *
     * @return void
     */
    protected function createCmsPageLocalizedAttributes(ArrayObject $cmsPageLocalizedAttributesTransfers, SpyCmsPage $pageEntity): void
    {
        foreach ($cmsPageLocalizedAttributesTransfers as $localizedAttributesTransfer) {
            $pageLocalizedAttributesEntity = new SpyCmsPageLocalizedAttributes();
            $pageLocalizedAttributesEntity->fromArray($localizedAttributesTransfer->modifiedToArray());
            $pageLocalizedAttributesEntity->setFkCmsPage($pageEntity->getIdCmsPage());
            $pageLocalizedAttributesEntity->save();

            $localizedAttributesTransfer->fromArray($pageLocalizedAttributesEntity->toArray(), true);
        }
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\CmsPageLocalizedAttributesTransfer[]|\ArrayObject $cmsPageLocalizedAttributesTransfers
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $pageEntity
     *
     * @return void
     */
    protected function updateCmsPageLocalizedAttributes(ArrayObject $cmsPageLocalizedAttributesTransfers, SpyCmsPage $pageEntity): void
    {
        foreach ($cmsPageLocalizedAttributesTransfers as $localizedAttributesTransfer) {
            $cmsPageLocalizedAttributesEntity = $this->getLocalizedAttributesForPage($pageEntity, $localizedAttributesTransfer);
            $cmsPageLocalizedAttributesEntity->fromArray($localizedAttributesTransfer->modifiedToArray());
            $cmsPageLocalizedAttributesEntity->save();

            $localizedAttributesTransfer->fromArray($cmsPageLocalizedAttributesEntity->toArray(), true);
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $pageEntity
     * @param \Generated\Shared\Transfer\CmsPageLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function getLocalizedAttributesForPage(SpyCmsPage $pageEntity, CmsPageLocalizedAttributesTransfer $localizedAttributesTransfer): SpyCmsPageLocalizedAttributes
    {
        $cmsPageLocalizedAttributesQuery = $this->cmsQueryContainer
            ->queryCmsPageLocalizedAttributes()
            ->filterByFkCmsPage($pageEntity->getIdCmsPage());

        if ($localizedAttributesTransfer->getIdCmsPageLocalizedAttributes()) {
            $cmsPageLocalizedAttributesQuery->filterByIdCmsPageLocalizedAttributes($localizedAttributesTransfer->getIdCmsPageLocalizedAttributes());
        } else {
            $fkLocale = $localizedAttributesTransfer
                ->requireFkLocale()
                ->getFkLocale();

            $cmsPageLocalizedAttributesQuery->filterByFkLocale($fkLocale);
        }

        return $cmsPageLocalizedAttributesQuery->findOneOrCreate();
    }
}
