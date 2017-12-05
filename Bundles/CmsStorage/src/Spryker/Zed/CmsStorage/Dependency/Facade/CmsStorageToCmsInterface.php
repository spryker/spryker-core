<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Dependency\Facade;

use Generated\Shared\Transfer\CmsPageDataTransfer;

interface CmsStorageToCmsInterface
{

    /**
     * @param \Generated\Shared\Transfer\CmsPageDataTransfer $cmsPageDataTransfer
     * @param string $data
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageDataTransfer
     */
    public function expandCmsPageDataTransfer(CmsPageDataTransfer $cmsPageDataTransfer, $data, $localeName);

}
