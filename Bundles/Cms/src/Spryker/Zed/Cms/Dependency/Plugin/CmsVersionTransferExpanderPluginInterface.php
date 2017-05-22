<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Plugin;

use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsVersionTransferExpanderPluginInterface
{

    /**
     * Specification:
     * - This plugin interface is used for expanding additional information in
     *   CmsVersionTransfer, this method will call when VersionFinder return a
     *   Cms Version
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function expandTransfer(CmsVersionTransfer $cmsVersionTransfer);

}
