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
     * @param \Generated\Shared\Transfer\PageTransfer $page
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     * @throws \Spryker\Zed\Cms\Business\Exception\PageExistsException
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePage(PageTransfer $page)
    {
        $this->checkTemplateExists($page->getFkTemplate());

        if ($page->getIdCmsPage() === null) {
            return $this->createPage($page);
        } else {
            return $this->updatePage($page);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    protected function createPage(PageTransfer $page)
    {
        $this->checkTemplateExists($page->getFkTemplate());

        $pageEntity = new SpyCmsPage();

        $pageEntity->fromArray($page->toArray());
        $pageEntity->save();

        $page->setIdCmsPage($pageEntity->getIdCmsPage());

        return $page;
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    protected function updatePage(PageTransfer $page)
    {
        $pageEntity = $this->getPageById($page->getIdCmsPage());
        $pageEntity->fromArray($page->toArray());

        if (!$pageEntity->isModified()) {
            return $page;
        }

        $pageEntity->save();

        return $page;
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
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $page
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function convertPageEntityToTransfer(SpyCmsPage $page)
    {
        $pageTransfer = new PageTransfer();
        $pageTransfer->fromArray($page->toArray());

        return $pageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     *
     * @var \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping[]
     *
     * @return void
     */
    public function touchPageActive(PageTransfer $page)
    {
        $pageMappings = $this->cmsQueryContainer->queryGlossaryKeyMappingsByPageId($page->getIdCmsPage())
            ->find();
        foreach ($pageMappings as $pageMapping) {
            $this->glossaryFacade->touchCurrentTranslationForKeyId($pageMapping->getFkGlossaryKey());
        }

        $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $page->getIdCmsPage());
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param string $url
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrl(PageTransfer $page, $url)
    {
        $this->checkPageExists($page->getIdCmsPage());

        return $this->urlFacade->createUrlForCurrentLocale($url, CmsConstants::RESOURCE_TYPE_PAGE, $page->getIdCmsPage());
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrlWithLocale(PageTransfer $page, $url, LocaleTransfer $localeTransfer)
    {
        $this->checkPageExists($page->getIdCmsPage());

        return $this->urlFacade->createUrl($url, $localeTransfer, CmsConstants::RESOURCE_TYPE_PAGE, $page->getIdCmsPage());
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $pageTransfer, $url)
    {
        if (!$this->hasPageId($pageTransfer->getIdCmsPage())) {
            $pageTransfer = $this->savePage($pageTransfer);
        }

        $urlTransfer = $this->createPageUrl($pageTransfer, $url);
        $this->urlFacade->touchUrlActive($urlTransfer->getIdUrl());

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $blockTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePageBlockAndTouch(PageTransfer $page, CmsBlockTransfer $blockTransfer)
    {
        $pageTransfer = $this->savePage($page);
        $blockTransfer->setFkPage($pageTransfer->getIdCmsPage());

        $this->blockManager->saveBlockAndTouch($blockTransfer);

        return $pageTransfer;
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

}
