<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Communication\Plugin;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockUpdatePluginInterface
{
    /**
     * Specification:
     * - Handles persistence of CMS block for plugins
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function handleUpdate(CmsBlockTransfer $cmsBlockTransfer): void;
}
