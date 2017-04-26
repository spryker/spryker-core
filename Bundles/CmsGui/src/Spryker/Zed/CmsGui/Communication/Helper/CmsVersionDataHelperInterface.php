<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Helper;

use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsVersionDataHelperInterface
{

    /**
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function extractCmsPageTransfer(CmsVersionTransfer $cmsVersionTransfer);

    /**
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function extractCmsGlossaryPageTransfer(CmsVersionTransfer $cmsVersionTransfer);

}
