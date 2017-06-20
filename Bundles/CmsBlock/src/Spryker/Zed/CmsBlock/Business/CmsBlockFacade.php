<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsBlock\Business\CmsBlockBusinessFactory getFactory()
 */
class CmsBlockFacade extends AbstractFacade implements CmsBlockFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function findCmsBlockById($idCmsBlock)
    {
        return $this->getFactory()
            ->createCmsBlockReader()
            ->findCmsBlockById($idCmsBlock);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById($idCmsBlock)
    {
        $this->getFactory()
            ->createCmsBlockWrite()
            ->activateById($idCmsBlock);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById($idCmsBlock)
    {
        $this->getFactory()
            ->createCmsBlockWrite()
            ->deactivateById($idCmsBlock);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->getFactory()
            ->createCmsBlockWrite()
            ->updateCmsBlock($cmsBlockTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->getFactory()
            ->createCmsBlockWrite()
            ->createCmsBlock($cmsBlockTransfer);
    }

    /**
     * @api
     *
     * @param string $templatePath
     *
     * @return bool
     */
    public function syncTemplate($templatePath)
    {
        return $this->getFactory()
            ->createCmsBlockTemplateManager()
            ->syncTemplate($templatePath);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function findGlossaryPlaceholders($idCmsBlock)
    {
        return $this->getFactory()
            ->createCmsBlockGlossaryManager()
            ->findPlaceholders($idCmsBlock);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer)
    {
        return $this->getFactory()
            ->createCmsBlockGlossaryWriter()
            ->saveGlossary($cmsBlockGlossaryTransfer);
    }

}
