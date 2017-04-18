<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Helper;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsVersionDataHelperInterface
{

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsPageTransfer
     */
    public function extractCmsPageTransfer(CmsVersionTransfer $cmsVersionTransfer);

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsGlossaryTransfer
     */
    public function extractCmsGlossaryPageTransfer(CmsVersionTransfer $cmsVersionTransfer);
}
