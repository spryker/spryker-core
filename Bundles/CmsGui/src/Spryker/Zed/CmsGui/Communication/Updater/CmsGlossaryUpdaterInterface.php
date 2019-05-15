<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Updater;

use Generated\Shared\Transfer\CmsGlossaryTransfer;

interface CmsGlossaryUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function updateAfterFind(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer;

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function updateBeforeSave(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer;
}
