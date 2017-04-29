<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Mapper;

use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsVersionMapperInterface
{

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionDataTransfer
     */
    public function mapToCmsVersionDataTransfer(CmsVersionTransfer $cmsVersionTransfer);

}
