<?php

namespace SprykerFeature\Zed\Cms\Business\Page;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Propel\Runtime\Exception\PropelException;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\UrlUrlTransfer;
use SprykerFeature\Zed\Cms\Business\Exception\MissingPageException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\PageExistsException;
use SprykerFeature\Zed\Cms\Business\Template\TemplateManagerInterface;
use SprykerFeature\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use SprykerFeature\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use SprykerFeature\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMapping;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPage;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

class PageManager implements PageManagerInterface
{
    const ITEM_TYPE_PAGE = 'page';
    /**
     * @var CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var TemplateManagerInterface
     */
    protected $templateManager;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var CmsToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var CmsToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var CmsToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param CmsQueryContainerInterface $cmsQueryContainer
     * @param TemplateManagerInterface $templateManager
     * @param CmsToGlossaryInterface $glossaryFacade
     * @param CmsToTouchInterface $touchFacade
     * @param CmsToUrlInterface $urlFacade
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        TemplateManagerInterface $templateManager,
        CmsToGlossaryInterface $glossaryFacade,
        CmsToTouchInterface $touchFacade,
        CmsToUrlInterface $urlFacade,
        LocatorLocatorInterface $locator
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->templateManager = $templateManager;
        $this->locator = $locator;
        $this->glossaryFacade = $glossaryFacade;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param Page $page
     *
     * @return Page
     * @throws MissingTemplateException
     * @throws MissingPageException
     * @throws PageExistsException
     */
    public function savePage(Page $page)
    {
        $this->checkTemplateExists($page->getFkTemplate());

        if (is_null($page->getIdCmsPage())) {
            return $this->createPage($page);
        } else {
            return $this->updatePage($page);
        }
    }

    /**
     * @param Page $page
     *
     * @return Page
     * @throws MissingTemplateException
     * @throws \Exception
     * @throws PropelException
     */
    protected function createPage(Page $page)
    {
        $this->checkTemplateExists($page->getFkTemplate());

        $pageEntity = $this->locator->cms()->entitySpyCmsPage();

        $pageEntity->fromArray($page->toArray());
        $pageEntity->save();

        $page->setIdCmsPage($pageEntity->getIdCmsPage());

        return $page;
    }

    /**
     * @param Page $page
     *
     * @return Page
     * @throws MissingPageException
     * @throws \Exception
     * @throws PropelException
     */
    protected function updatePage(Page $page)
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
     * @throws MissingTemplateException
     */
    protected function checkTemplateExists($idTemplate)
    {
        if (!$this->templateManager->hasTemplateId($idTemplate)) {
            throw new MissingTemplateException(
                sprintf(
                    'Tried to save page referring to a missing template with id %s',
                    $idTemplate
                )
            );
        }
    }

    /**
     * @param int $idPage
     *
     * @throws MissingPageException
     */
    protected function checkPageExists($idPage)
    {
        if (!$this->hasPageId($idPage)) {
            throw new MissingPageException(
                sprintf(
                    'Tried to refer to a missing page with id %s',
                    $idPage
                )
            );
        }
    }

    /**
     * @param int $idPage
     *
     * @return SpyCmsPage
     * @throws MissingPageException
     */
    public function getPageById($idPage)
    {
        $page = $this->cmsQueryContainer->queryPageById($idPage)->findOne();
        if (!$page) {
            throw new MissingPageException(
                sprintf(
                    'Tried to retrieve a missing page with id %s',
                    $idPage
                )
            );
        }

        return $page;
    }

    /**
     * @param SpyCmsPage $page
     *
     * @return Page
     */
    public function convertPageEntityToTransfer(SpyCmsPage $page)
    {
        $pageTransfer = new \Generated\Shared\Transfer\CmsPageTransfer();
        $pageTransfer->fromArray($page->toArray());

        return $pageTransfer;
    }

    /**
     * @param Page $page
     * @var SpyCmsGlossaryKeyMapping[] $pageMappings
     */
    public function touchPageActive(Page $page)
    {
        $pageMappings = $this->cmsQueryContainer->queryGlossaryKeyMappingsByPageId($page->getIdCmsPage())->find();
        foreach ($pageMappings as $pageMapping) {
            $this->glossaryFacade->touchCurrentTranslationForKeyId($pageMapping->getFkGlossaryKey());
        }

        $this->touchFacade->touchActive(self::ITEM_TYPE_PAGE, $page->getIdCmsPage());
    }

    /**
     * @param Page $page
     * @param string $url
     *
     * @return Url
     * @throws UrlExistsException
     */
    public function createPageUrl(Page $page, $url)
    {
        $this->checkPageExists($page->getIdCmsPage());

        return $this->urlFacade->createUrlForCurrentLocale($url, 'page', $page->getIdCmsPage());
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
