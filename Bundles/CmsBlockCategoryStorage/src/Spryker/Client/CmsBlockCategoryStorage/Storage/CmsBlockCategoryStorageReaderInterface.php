<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockCategoryStorage\Storage;

use Generated\Shared\Transfer\CmsBlockRequestTransfer;

interface CmsBlockCategoryStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockRequestTransfer $cmsBlockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksByOptions(CmsBlockRequestTransfer $cmsBlockRequestTransfer): array;
}
