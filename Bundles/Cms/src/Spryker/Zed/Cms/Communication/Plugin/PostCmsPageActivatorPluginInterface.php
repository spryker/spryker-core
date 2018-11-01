<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Plugin;

use Generated\Shared\Transfer\CmsPageTransfer;

interface PostCmsPageActivatorPluginInterface
{
    /**
     * Specification:
     * - Runs after the CMS activator class functions (activate and deactivate)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return void
     */
    public function execute(CmsPageTransfer $cmsPageTransfer);
}
