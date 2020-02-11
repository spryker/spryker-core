<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockProductStorage\Storage;

use Generated\Shared\Transfer\CmsBlockRequestTransfer;

interface CmsBlockProductStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockRequestTransfer $cmsBlockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksByOptions(CmsBlockRequestTransfer $cmsBlockRequestTransfer): array;
}
