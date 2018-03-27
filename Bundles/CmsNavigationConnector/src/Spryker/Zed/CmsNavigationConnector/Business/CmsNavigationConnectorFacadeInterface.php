<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsNavigationConnector\Business;

use Generated\Shared\Transfer\CmsPageTransfer;

interface CmsNavigationConnectorFacadeInterface
{
    /**
     * Specification:
     * - Updates Navigation nodes is active status based on Cms page transfer given
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return void
     */
    public function updateCmsPageNavigationNodesIsActive(CmsPageTransfer $cmsPageTransfer);
}
