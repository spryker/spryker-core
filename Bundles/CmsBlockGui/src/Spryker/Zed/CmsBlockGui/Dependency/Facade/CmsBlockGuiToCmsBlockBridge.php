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
    public function findCmsBlockById($idCmsBlock)
    {
        return $this->cmsBlockFacade->findCmsBlockById($idCmsBlock);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById($idCmsBlock)
    {
        $this->cmsBlockFacade->activateById($idCmsBlock);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById($idCmsBlock)
    {
        $this->cmsBlockFacade->deactivateById($idCmsBlock);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->cmsBlockFacade->updateCmsBlock($cmsBlockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->cmsBlockFacade->createCmsBlock($cmsBlockTransfer);
    }

    /**
     * @param string $templatePath
     *
     * @return void
     */
    public function syncTemplate($templatePath)
    {
        $this->cmsBlockFacade->syncTemplate($templatePath);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function findGlossaryPlaceholders($idCmsBlock)
    {
        return $this->cmsBlockFacade->findGlossaryPlaceholders($idCmsBlock);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer)
    {
        return $this->cmsBlockFacade->saveGlossary($cmsBlockGlossaryTransfer);
    }

}
