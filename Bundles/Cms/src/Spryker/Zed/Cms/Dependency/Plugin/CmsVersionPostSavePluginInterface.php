<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Plugin;

use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsVersionPostSavePluginInterface
{

    /**
     * Specification:
     * - This plugin interface is used for post hook saving when the cms page
     *   gets published
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function postSave(CmsVersionTransfer $cmsVersionTransfer);

}
