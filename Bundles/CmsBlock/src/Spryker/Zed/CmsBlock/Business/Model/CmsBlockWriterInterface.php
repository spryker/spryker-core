<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockWriterInterface
{
    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById(int $idCmsBlock): void;

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById(int $idCmsBlock): void;

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer): CmsBlockTransfer;

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer): CmsBlockTransfer;
}
