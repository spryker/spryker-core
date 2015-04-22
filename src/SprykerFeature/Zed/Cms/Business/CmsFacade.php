<?php

namespace SprykerFeature\Zed\Cms\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Shared\Cms\Transfer\CmsTemplate;
use SprykerFeature\Shared\Cms\Transfer\Page;
use SprykerFeature\Shared\Cms\Transfer\PageKeyMapping;
use SprykerFeature\Shared\Url\Transfer\Url;
use SprykerFeature\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingPageException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\TemplateExistsException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingTranslationException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 */
class CmsFacade extends AbstractFacade
{
    /**
     * @param string $name
     * @param string $path
     *
     * @return CmsTemplate
     * @throws TemplateExistsException
     */
    public function createTemplate($name, $path)
    {
        $templateManager = $this->getDependencyContainer()->getTemplateManager();

        return $templateEntity = $templateManager->createTemplate($name, $path);
    }

    /**
     * @param string $path
     *
     * @return CmsTemplate
     * @throws MissingTemplateException
     */
    public function getTemplate($path)
    {
        $templateManager = $this->getDependencyContainer()->getTemplateManager();

        return $templateManager->getTemplateByPath($path);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function hasTemplate($path)
    {
        $templateManager = $this->getDependencyContainer()->getTemplateManager();

        return $templateManager->hasTemplatePath($path);
    }

    /**
     * @param Page $page
     *
     * @return Page
     * @throws MissingPageException
     */
    public function savePage(Page $page)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->savePage($page);
    }

    /**
     * @param PageKeyMapping $pageKeyMapping
     *
     * @return PageKeyMapping
     */
    public function savePageKeyMapping(PageKeyMapping $pageKeyMapping)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->savePageKeyMapping($pageKeyMapping);
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->hasPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return PageKeyMapping
     * @throws MissingGlossaryKeyMappingException
     */
    public function getPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->getPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param CmsTemplate $cmsTemplate
     *
     * @return CmsTemplate
     */
    public function saveTemplate(CmsTemplate $cmsTemplate)
    {
        $templateManager = $this->getDependencyContainer()->getTemplateManager();

        return $templateManager->saveTemplate($cmsTemplate);
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @return string
     * @throws MissingGlossaryKeyMappingException
     * @throws MissingTranslationException
     */
    public function translatePlaceholder($idPage, $placeholder, array $data = [])
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->translatePlaceholder($idPage, $placeholder, $data);
    }

    /**
     * @param Page $page
     * @param string $placeholder
     * @param string $value
     *
     * @return PageKeyMapping
     */
    public function addPlaceholderText(Page $page, $placeholder, $value)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->addPlaceholderText($page, $placeholder, $value);
    }

    /**
     * @param Page $page
     * @param string $placeholder
     *
     * @return bool
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    public function deletePageKeyMapping(Page $page, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->deletePageKeyMapping($page, $placeholder);
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
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->createPageUrl($page, $url);
    }

    /**
     * @param Page $page
     */
    public function touchPageActive(Page $page)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();
        $pageManager->touchPageActive($page);
    }
}
