<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Business\Block\BlockManagerInterface;
use Spryker\Zed\Cms\Business\Exception\LocaleNotFoundException;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Exception\MissingTemplateException;
use Spryker\Zed\Cms\Business\Template\TemplateManagerInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
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
     * @var \Spryker\Zed\Cms\Business\Block\BlockManagerInterface
     */
    protected $blockManager;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface $templateManager
     * @param \Spryker\Zed\Cms\Business\Block\BlockManagerInterface $blockManager
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface $touchFacade
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface $urlFacade
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer, TemplateManagerInterface $templateManager, BlockManagerInterface $blockManager, CmsToGlossaryInterface $glossaryFacade, CmsToTouchInterface $touchFacade, CmsToUrlInterface $urlFacade)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->templateManager = $templateManager;
        $this->blockManager = $blockManager;
        $this->glossaryFacade = $glossaryFacade;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePage(PageTransfer $pageTransfer)
    {
        $this->checkTemplateExists($pageTransfer->getFkTemplate());

        if ($pageTransfer->getIdCmsPage() === null) {
            return $this->createPage($pageTransfer);
        }

        return $this->updatePage($pageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    protected function createPage(PageTransfer $pageTransfer)
    {
        $this->checkTemplateExists($pageTransfer->getFkTemplate());

        $pageEntity = new SpyCmsPage();

        $pageEntity->fromArray($pageTransfer->toArray());
        $pageEntity->save();

        $pageTransfer->setIdCmsPage($pageEntity->getIdCmsPage());

        return $pageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    protected function updatePage(PageTransfer $pageTransfer)
    {
        $pageEntity = $this->getPageById($pageTransfer->getIdCmsPage());
        $pageEntity->fromArray($pageTransfer->modifiedToArray());

        if ($pageTransfer->getUrl() !== null) {
            $this->updatePageUrl($pageTransfer);
        }

        if (!$pageEntity->isModified()) {
            return $pageTransfer;
        }

        $pageEntity->save();

        return $pageTransfer;
    }

    /**
     * @param int $idTemplate
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     *
     * @return void
     */
    protected function checkTemplateExists($idTemplate)
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
    protected function checkPageExists($idPage)
    {
        if (!$this->hasPageId($idPage)) {
            throw new MissingPageException(sprintf('Tried to refer to a missing page with id %s', $idPage));
        }
    }

    /**
     * @param int $idPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    public function getPageById($idPage)
    {
        $page = $this->cmsQueryContainer->queryPageById($idPage)
            ->findOne();
        if (!$page) {
            throw new MissingPageException(sprintf('Tried to retrieve a missing page with id %s', $idPage));
        }

        return $page;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $pageEntity
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function convertPageEntityToTransfer(SpyCmsPage $pageEntity)
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
    public function touchPageActive(PageTransfer $pageTransfer, LocaleTransfer $localeTransfer = null)
    {
        $pageMappings = $this->cmsQueryContainer->queryGlossaryKeyMappingsByPageId($pageTransfer->getIdCmsPage())
            ->find();
        foreach ($pageMappings as $pageMapping) {
            $this->glossaryFacade->touchTranslationForKeyId($pageMapping->getFkGlossaryKey(), $localeTransfer);
        }

        $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $pageTransfer->getIdCmsPage(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrl(PageTransfer $pageTransfer)
    {
        $this->checkPageExists($pageTransfer->getIdCmsPage());
        $idLocale = $pageTransfer->getUrl()->getFkLocale();

        return $this->urlFacade->createUrl(
            $pageTransfer->getUrl()->getUrl(),
            $this->getLocaleTransfer($idLocale),
            CmsConstants::RESOURCE_TYPE_PAGE,
            $pageTransfer->getIdCmsPage()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function updatePageUrl(PageTransfer $pageTransfer)
    {
        $this->checkPageExists($pageTransfer->getIdCmsPage());

        return $this->urlFacade->saveUrlAndTouch($pageTransfer->getUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrlWithLocale(PageTransfer $pageTransfer, $url, LocaleTransfer $localeTransfer)
    {
        $this->checkPageExists($pageTransfer->getIdCmsPage());

        return $this->urlFacade->createUrl($url, $localeTransfer, CmsConstants::RESOURCE_TYPE_PAGE, $pageTransfer->getIdCmsPage());
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $pageTransfer)
    {
        if (!$this->hasPageId($pageTransfer->getIdCmsPage())) {
            $pageTransfer = $this->savePage($pageTransfer);
        }

        $urlTransfer = $this->createPageUrl($pageTransfer);
        $this->urlFacade->touchUrlActive($urlTransfer->getIdUrl());

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $blockTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePageBlockAndTouch(PageTransfer $pageTransfer, CmsBlockTransfer $blockTransfer)
    {
        $savedPageTransfer = $this->savePage($pageTransfer);
        $blockTransfer->setFkPage($savedPageTransfer->getIdCmsPage());

        $this->blockManager->saveBlockAndTouch($blockTransfer);

        return $savedPageTransfer;
    }

    /**
     * @param int $idPage
     *
     * @return bool
     */
    protected function hasPageId($idPage)
    {
        $query = $this->cmsQueryContainer->queryPageById($idPage);

        return $query->count() > 0;
    }

    /**
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\LocaleNotFoundException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer($idLocale)
    {
        $localEntity = $this->cmsQueryContainer->queryLocaleById($idLocale)->findOne();

        if ($localEntity === null) {
            throw new LocaleNotFoundException(
                sprintf('Locale with id %s not found', $idLocale)
            );
        }

        $localTransfer = new LocaleTransfer();
        $localTransfer->fromArray($localEntity->toArray());

        return $localTransfer;
    }

}
