<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Business;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Exception\MissingTemplateException;
use Spryker\Zed\Cms\Business\Exception\TemplateExistsException;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

/**
 * @method CmsDependencyContainer getBusinessFactory()
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
        $templateManager = $this->getBusinessFactory()->getTemplateManager();

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
        $templateManager = $this->getBusinessFactory()->getTemplateManager();

        return $templateManager->getTemplateByPath($path);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function hasTemplate($path)
    {
        $templateManager = $this->getBusinessFactory()->getTemplateManager();

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
        $pageManager = $this->getBusinessFactory()->getPageManager();

        return $pageManager->savePage($pageTransfer);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function saveBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getBusinessFactory()->getBlockManager();

        return $blockManager->saveBlock($cmsBlockTransfer);
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMappingTransfer
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMappingTransfer)
    {
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->savePageKeyMapping($pageKeyMappingTransfer);
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMappingTransfer
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMappingAndTouch(PageKeyMappingTransfer $pageKeyMappingTransfer)
    {
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

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
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->hasPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param int $idCategory
     *
     * @return bool
     */
    public function hasBlockCategoryNodeMapping($idCategory)
    {
        $blockManager = $this->getBusinessFactory()->getBlockManager();

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
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->getPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param CmsTemplateTransfer $cmsTemplateTransfer
     *
     * @return CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplateTransfer)
    {
        $templateManager = $this->getBusinessFactory()->getTemplateManager();

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
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

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
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

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
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

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
        $pageManager = $this->getBusinessFactory()->getPageManager();

        return $pageManager->createPageUrl($pageTransfer, $url);
    }

    /**
     * @param PageTransfer $pageTransfer
     *
     * @return void
     */
    public function touchPageActive(PageTransfer $pageTransfer)
    {
        $pageManager = $this->getBusinessFactory()->getPageManager();
        $pageManager->touchPageActive($pageTransfer);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function touchBlockActive(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getBusinessFactory()->getBlockManager();
        $blockManager->touchBlockActive($cmsBlockTransfer);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function touchBlockDelete(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getBusinessFactory()->getBlockManager();
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
        $pageManager = $this->getBusinessFactory()->getPageManager();

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
        $pageManager = $this->getBusinessFactory()->getPageManager();

        return $pageManager->savePageBlockAndTouch($pageTransfer, $blockTransfer);
    }

    /**
     * @param int $idPage
     *
     * @return bool
     */
    public function deleteGlossaryKeysByIdPage($idPage)
    {
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->deleteGlossaryKeysByIdPage($idPage);
    }

    /**
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate($cmsTemplateFolderPath)
    {
        $templateManager = $this->getBusinessFactory()->getTemplateManager();

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
        $glossaryKeyMappingManager = $this->getBusinessFactory()->getGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->generateGlossaryKeyName($templateName, $placeholder);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode)
    {
        $blockManager = $this->getBusinessFactory()->getBlockManager();

        $blockManager->updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return CmsBlockTransfer[]
     */
    public function getCmsBlocksByIdCategoryNode($idCategoryNode)
    {
        $blockManager = $this->getBusinessFactory()->getBlockManager();

        return $blockManager->getCmsBlocksByIdCategoryNode($idCategoryNode);
    }

}
