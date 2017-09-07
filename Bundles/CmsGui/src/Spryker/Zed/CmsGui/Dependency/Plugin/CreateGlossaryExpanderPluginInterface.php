<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Dependency\Plugin;

use Generated\Shared\Transfer\CmsPageTransfer;

interface CreateGlossaryExpanderPluginInterface
{

    /**
     * Specification:
     * TODO: to do
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function getViewActionButtons(CmsPageTransfer $cmsPageTransfer);

}
