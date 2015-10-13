<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
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
     * @throws TemplateExistsException
     *
     * @return CmsTemplateTransfer
     */
    public function createTemplate($name, $path)
    {
        $templateManager = $this->getDependencyContainer()->getTemplateManager();

        return $templateEntity = $templateManager->createTemplate($name, $path);
    }

    /**
     * @param string $path
     *
     * @throws MissingTemplateException
     *
     * @return CmsTemplateTransfer
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
     * @param PageTransfer $pageTransfer
     *
     * @throws MissingPageException
     *
     * @return PageTransfer
     */
    public function savePage(PageTransfer $pageTransfer)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->savePage($pageTransfer);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function saveBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getDependencyContainer()->getBlockManager();

        return $blockManager->saveBlock($cmsBlockTransfer);
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMappingTransfer
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMappingTransfer)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->savePageKeyMapping($pageKeyMappingTransfer);
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMappingTransfer
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMappingAndTouch(PageKeyMappingTransfer $pageKeyMappingTransfer)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->savePageKeyMappingAndTouch($pageKeyMappingTransfer);
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
     * @param int $idCategory
     *
     * @return bool
     */
    public function hasBlockCategoryNodeMapping($idCategory)
    {
        $blockManager = $this->getDependencyContainer()->getBlockManager();

        return $blockManager->hasBlockCategoryNodeMapping($idCategory);
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @throws MissingGlossaryKeyMappingException
     *
     * @return PageKeyMappingTransfer
     */
    public function getPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->getPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param CmsTemplateTransfer $cmsTemplateTransfer
     *
     * @return CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplateTransfer)
    {
        $templateManager = $this->getDependencyContainer()->getTemplateManager();

        return $templateManager->saveTemplate($cmsTemplateTransfer);
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @throws MissingGlossaryKeyMappingException
     * @throws MissingTranslationException
     *
     * @return string
     */
    public function translatePlaceholder($idPage, $placeholder, array $data = [])
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->translatePlaceholder($idPage, $placeholder, $data);
    }

    /**
     * @param PageTransfer $pageTransfer
     * @param string $placeholder
     * @param string $value
     *
     * @return PageKeyMappingTransfer
     */
    public function addPlaceholderText(PageTransfer $pageTransfer, $placeholder, $value)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->addPlaceholderText($pageTransfer, $placeholder, $value);
    }

    /**
     * @param PageTransfer $pageTransfer
     * @param string $placeholder
     *
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     *
     * @return bool
     */
    public function deletePageKeyMapping(PageTransfer $pageTransfer, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->deletePageKeyMapping($pageTransfer, $placeholder);
    }

    /**
     * @param PageTransfer $pageTransfer
     * @param string $url
     *
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createPageUrl(PageTransfer $pageTransfer, $url)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->createPageUrl($pageTransfer, $url);
    }

    /**
     * @param PageTransfer $pageTransfer
     */
    public function touchPageActive(PageTransfer $pageTransfer)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();
        $pageManager->touchPageActive($pageTransfer);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     */
    public function touchBlockActive(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getDependencyContainer()->getBlockManager();
        $blockManager->touchBlockActive($cmsBlockTransfer);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     */
    public function touchBlockDelete(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getDependencyContainer()->getBlockManager();
        $blockManager->touchBlockDelete($cmsBlockTransfer);
    }

    /**
     * @param PageTransfer $pageTransfer
     * @param string $url
     *
     * @return UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $pageTransfer, $url)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->savePageUrlAndTouch($pageTransfer, $url);
    }

    /**
     * @param PageTransfer $pageTransfer
     * @param CmsBlockTransfer $blockTransfer
     *
     * @return PageTransfer
     */
    public function savePageBlockAndTouch(PageTransfer $pageTransfer, CmsBlockTransfer $blockTransfer)
    {
        $pageManager = $this->getDependencyContainer()->getPageManager();

        return $pageManager->savePageBlockAndTouch($pageTransfer, $blockTransfer);
    }

    /**
     * @param int $idPage
     *
     * @return bool
     */
    public function deleteGlossaryKeysByIdPage($idPage)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->deleteGlossaryKeysByIdPage($idPage);
    }

    /**
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate($cmsTemplateFolderPath)
    {
        $templateManager = $this->getDependencyContainer()->getTemplateManager();

        return $templateManager->syncTemplate($cmsTemplateFolderPath);
    }

    /**
     * @param string $templateName
     * @param string $placeholder
     *
     * @return string
     */
    public function generateGlossaryKeyName($templateName, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getDependencyContainer()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->generateGlossaryKeyName($templateName, $placeholder);
    }

    /**
     * @param int $idCategoryNode
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode)
    {
        $blockManager = $this->getDependencyContainer()->getBlockManager();

        $blockManager->updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);
    }
}
