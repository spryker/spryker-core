<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Propel\Runtime\Exception\PropelException;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\UrlTransfer;
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
     * @return CmsTemplateTransfer
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
     * @return CmsTemplateTransfer
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
     * @param PageTransfer $page
     *
     * @return PageTransfer
     * @throws MissingPageException
     */
    public function savePage(PageTransfer $page)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->savePage($page);
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMapping
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMapping)
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
     * @return PageKeyMappingTransfer
     * @throws MissingGlossaryKeyMappingException
     */
    public function getPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->getPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param CmsTemplateTransfer $cmsTemplate
     *
     * @return CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplate)
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
     * @param PageTransfer $page
     * @param string $placeholder
     * @param string $value
     *
     * @return PageKeyMappingTransfer
     */
    public function addPlaceholderText(PageTransfer $page, $placeholder, $value)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->addPlaceholderText($page, $placeholder, $value);
    }

    /**
     * @param PageTransfer $page
     * @param string $placeholder
     *
     * @return bool
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    public function deletePageKeyMapping(PageTransfer $page, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->deletePageKeyMapping($page, $placeholder);
    }

    /**
     * @param PageTransfer $page
     * @param string $url
     *
     * @return UrlTransfer
     * @throws UrlExistsException
     */
    public function createPageUrl(PageTransfer $page, $url)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->createPageUrl($page, $url);
    }

    /**
     * @param PageTransfer $page
     */
    public function touchPageActive(PageTransfer $page)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();
        $pageManager->touchPageActive($page);
    }
}
