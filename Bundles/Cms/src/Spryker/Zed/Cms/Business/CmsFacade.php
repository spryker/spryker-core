<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Cms\Business\CmsBusinessFactory getFactory()
 */
class CmsFacade extends AbstractFacade implements CmsFacadeInterface
{

    /**
     * @api
     *
     * @param string $name
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function createTemplate($name, $path)
    {
        $templateManager = $this->getFactory()->createTemplateManager();

        return $templateEntity = $templateManager->createTemplate($name, $path);
    }

    /**
     * @api
     *
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function getTemplate($path)
    {
        $templateManager = $this->getFactory()->createTemplateManager();

        return $templateManager->getTemplateByPath($path);
    }

    /**
     * @api
     *
     * @param string $path
     *
     * @return bool
     */
    public function hasTemplate($path)
    {
        $templateManager = $this->getFactory()->createTemplateManager();

        return $templateManager->hasTemplatePath($path);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePage(PageTransfer $pageTransfer)
    {
        $pageManager = $this->getFactory()->createPageManager();

        return $pageManager->savePage($pageTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function saveBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getFactory()->createBlockManager();

        return $blockManager->saveBlock($cmsBlockTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMappingTransfer
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMappingTransfer)
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->savePageKeyMapping($pageKeyMappingTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMappingTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function savePageKeyMappingAndTouch(PageKeyMappingTransfer $pageKeyMappingTransfer, LocaleTransfer $localeTransfer = null)
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->savePageKeyMappingAndTouch($pageKeyMappingTransfer, $localeTransfer);
    }

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->hasPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return bool
     */
    public function hasBlockCategoryNodeMapping($idCategory)
    {
        $blockManager = $this->getFactory()->createBlockManager();

        return $blockManager->hasBlockCategoryNodeMapping($idCategory);
    }

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function getPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->getPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsTemplateTransfer $cmsTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplateTransfer)
    {
        $templateManager = $this->getFactory()->createTemplateManager();

        return $templateManager->saveTemplate($cmsTemplateTransfer);
    }

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @return string
     */
    public function translatePlaceholder($idPage, $placeholder, array $data = [])
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->translatePlaceholder($idPage, $placeholder, $data);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $placeholder
     * @param string $value
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param bool $autoGlossaryKeyIncrement
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function addPlaceholderText(PageTransfer $pageTransfer, $placeholder, $value, LocaleTransfer $localeTransfer = null, $autoGlossaryKeyIncrement = true)
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->addPlaceholderText($pageTransfer, $placeholder, $value, $localeTransfer, $autoGlossaryKeyIncrement);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $placeholder
     *
     * @return bool
     */
    public function deletePageKeyMapping(PageTransfer $pageTransfer, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->deletePageKeyMapping($pageTransfer, $placeholder);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function touchPageActive(PageTransfer $pageTransfer, LocaleTransfer $localeTransfer = null)
    {
        $pageManager = $this->getFactory()->createPageManager();
        $pageManager->touchPageActive($pageTransfer, $localeTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function touchBlockActive(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getFactory()->createBlockManager();
        $blockManager->touchBlockActive($cmsBlockTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function touchBlockDelete(CmsBlockTransfer $cmsBlockTransfer)
    {
        $blockManager = $this->getFactory()->createBlockManager();
        $blockManager->touchBlockDelete($cmsBlockTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $pageTransfer)
    {
        $pageManager = $this->getFactory()->createPageManager();

        return $pageManager->savePageUrlAndTouch($pageTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $blockTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePageBlockAndTouch(PageTransfer $pageTransfer, CmsBlockTransfer $blockTransfer)
    {
        $pageManager = $this->getFactory()->createPageManager();

        return $pageManager->savePageBlockAndTouch($pageTransfer, $blockTransfer);
    }

    /**
     * @api
     *
     * @param int $idPage
     *
     * @return bool
     */
    public function deleteGlossaryKeysByIdPage($idPage)
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->deleteGlossaryKeysByIdPage($idPage);
    }

    /**
     * @api
     *
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate($cmsTemplateFolderPath)
    {
        $templateManager = $this->getFactory()->createTemplateManager();

        return $templateManager->syncTemplate($cmsTemplateFolderPath);
    }

    /**
     * @api
     *
     * @param string $templateName
     * @param string $placeholder
     *
     * @return string
     */
    public function generateGlossaryKeyName($templateName, $placeholder)
    {
        $glossaryKeyMappingManager = $this->getFactory()->createGlossaryKeyMappingManager();

        return $glossaryKeyMappingManager->generateGlossaryKeyName($templateName, $placeholder);
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode)
    {
        $blockManager = $this->getFactory()->createBlockManager();

        $blockManager->updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksByIdCategoryNode($idCategoryNode)
    {
        $blockManager = $this->getFactory()->createBlockManager();

        return $blockManager->getCmsBlocksByIdCategoryNode($idCategoryNode);
    }

    /**
     * Specification:
     * - Deletes Cms Page and its relations (urls, glossary key mappings) from database
     * - Touches deleted Cms Page to notify collector about the change
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deletePageById($idCmsPage)
    {
        $this->getFactory()
            ->createPageRemover()
            ->delete($idCmsPage);
    }

    /**
     * Specification:
     * - Deletes Cms Block and its relations (cms page, glossary key mappings) from database
     * - Touches deleted Cms Block to notify collector about the change
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deleteBlockById($idCmsBlock)
    {
        $this->getFactory()
            ->createBlockRemover()
            ->delete($idCmsBlock);
    }

}
