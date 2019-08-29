<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business\Storage;

interface CmsBlockCategoryStorageReaderInterface
{
    /**
     * @param array $cmsBlockOptions
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksByOptions(array $cmsBlockOptions): array;
}
