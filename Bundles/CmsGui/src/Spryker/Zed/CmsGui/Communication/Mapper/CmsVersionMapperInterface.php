<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Mapper;

use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsVersionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function mapToCmsVersionDataTransfer(CmsVersionTransfer $cmsVersionTransfer);
}
