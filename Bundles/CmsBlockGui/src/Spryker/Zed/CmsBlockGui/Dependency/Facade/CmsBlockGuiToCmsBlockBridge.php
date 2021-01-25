<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Dependency\Facade;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;

class CmsBlockGuiToCmsBlockBridge implements CmsBlockGuiToCmsBlockInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected $cmsBlockFacade;

    /**
     * @param \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface $cmsBlockFacade
     */
    public function __construct($cmsBlockFacade)
    {
        $this->cmsBlockFacade = $cmsBlockFacade;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockById($idCmsBlock): ?CmsBlockTransfer
    {
        return $this->cmsBlockFacade->findCmsBlockById($idCmsBlock);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById($idCmsBlock): void
    {
        $this->cmsBlockFacade->activateById($idCmsBlock);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById($idCmsBlock): void
    {
        $this->cmsBlockFacade->deactivateById($idCmsBlock);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer): CmsBlockTransfer
    {
        return $this->cmsBlockFacade->updateCmsBlock($cmsBlockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer): CmsBlockTransfer
    {
        return $this->cmsBlockFacade->createCmsBlock($cmsBlockTransfer);
    }

    /**
     * @param string $templatePath
     *
     * @return void
     */
    public function syncTemplate($templatePath): void
    {
        $this->cmsBlockFacade->syncTemplate($templatePath);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function findGlossary($idCmsBlock): CmsBlockGlossaryTransfer
    {
        return $this->cmsBlockFacade->findGlossary($idCmsBlock);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        return $this->cmsBlockFacade->saveGlossary($cmsBlockGlossaryTransfer);
    }

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return bool
     */
    public function hasTemplateFileById($idCmsBlockTemplate): bool
    {
        return $this->cmsBlockFacade->hasTemplateFileById($idCmsBlockTemplate);
    }
}
