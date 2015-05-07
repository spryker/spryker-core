<?php

namespace SprykerFeature\Zed\Cms\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Propel\Runtime\Exception\PropelException;
use Generated\Shared\Transfer\CmsCmsTemplateTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPageKeyMappingTransfer;
use Generated\Shared\Transfer\UrlUrlTransfer;
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
     * @return CmsCmsTemplateTransfer
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
     * @return CmsCmsTemplateTransfer
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
     * @param CmsPageTransfer $page
     *
     * @return CmsPageTransfer
     * @throws MissingPageException
     */
    public function savePage(CmsPageTransfer $page)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->savePage($page);
    }

    /**
     * @param CmsPageKeyMappingTransfer $pageKeyMapping
     *
     * @return CmsPageKeyMappingTransfer
     */
    public function savePageKeyMapping(CmsPageKeyMappingTransfer $pageKeyMapping)
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
     * @return CmsPageKeyMappingTransfer
     * @throws MissingGlossaryKeyMappingException
     */
    public function getPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->getPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param CmsCmsTemplateTransfer $cmsTemplate
     *
     * @return CmsCmsTemplateTransfer
     */
    public function saveTemplate(CmsCmsTemplateTransfer $cmsTemplate)
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
     * @param CmsPageTransfer $page
     * @param string $placeholder
     * @param string $value
     *
     * @return CmsPageKeyMappingTransfer
     */
    public function addPlaceholderText(CmsPageTransfer $page, $placeholder, $value)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->addPlaceholderText($page, $placeholder, $value);
    }

    /**
     * @param CmsPageTransfer $page
     * @param string $placeholder
     *
     * @return bool
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    public function deletePageKeyMapping(CmsPageTransfer $page, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->deletePageKeyMapping($page, $placeholder);
    }

    /**
     * @param CmsPageTransfer $page
     * @param string $url
     *
     * @return UrlUrlTransfer
     * @throws UrlExistsException
     */
    public function createPageUrl(CmsPageTransfer $page, $url)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->createPageUrl($page, $url);
    }

    /**
     * @param CmsPageTransfer $page
     */
    public function touchPageActive(CmsPageTransfer $page)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();
        $pageManager->touchPageActive($page);
    }
}
