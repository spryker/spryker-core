<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CmsPageTransfer;

interface PreCmsPageRelationDeletePluginInterface
{
    /**
     * Specification:
     * - Runs before delete CMS Page relations.
     * - Removes all indirect relations to CMS Page.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return void
     */
    public function execute(CmsPageTransfer $cmsPageTransfer): void;
}
