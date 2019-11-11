<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsBlock\Business\CmsBlockBusinessFactory getFactory()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface getRepository()
 */
class CmsBlockFacade extends AbstractFacade implements CmsBlockFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockById(int $idCmsBlock): ?CmsBlockTransfer
    {
        return $this->getFactory()
            ->createCmsBlockReader()
            ->findCmsBlockById($idCmsBlock);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById(int $idCmsBlock): void
    {
        $this->getFactory()
            ->createCmsBlockWrite()
            ->activateById($idCmsBlock);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById(int $idCmsBlock): void
    {
        $this->getFactory()
            ->createCmsBlockWrite()
            ->deactivateById($idCmsBlock);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockNotFoundException
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer): CmsBlockTransfer
    {
        return $this->getFactory()
            ->createCmsBlockWrite()
            ->updateCmsBlock($cmsBlockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer): CmsBlockTransfer
    {
        return $this->getFactory()
            ->createCmsBlockWrite()
            ->createCmsBlock($cmsBlockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $templatePath
     *
     * @return void
     */
    public function syncTemplate(string $templatePath): void
    {
        $this->getFactory()
            ->createCmsBlockTemplateManager()
            ->syncTemplate($templatePath);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function findGlossary(int $idCmsBlock): CmsBlockGlossaryTransfer
    {
        return $this->getFactory()
            ->createCmsBlockGlossaryManager()
            ->findPlaceholders($idCmsBlock);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockMappingAmbiguousException
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\MissingCmsBlockGlossaryKeyMapping
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        return $this->getFactory()
            ->createCmsBlockGlossaryWriter()
            ->saveGlossary($cmsBlockGlossaryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    public function createTemplate(string $name, string $path): CmsBlockTemplateTransfer
    {
        return $this->getFactory()
            ->createCmsBlockTemplateManager()
            ->createTemplate($name, $path);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer|null
     */
    public function findTemplate(string $path): ?CmsBlockTemplateTransfer
    {
        return $this->getFactory()
            ->createCmsBlockTemplateManager()
            ->findTemplateByPath($path);
    }

    /**
     * @inheritDoc
     *
     * @api
     *
     * @param int $idCmsBlockTemplate
     *
     * @return bool
     */
    public function hasTemplateFileById(int $idCmsBlockTemplate): bool
    {
        return $this->getFactory()
            ->createCmsBlockTemplateManager()
            ->hasTemplateFileById($idCmsBlockTemplate);
    }
}
