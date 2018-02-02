<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface DatasetSaverInterface
{
    /**
     * @param null|\Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return bool
     */
    public function save(SpyDatasetEntityTransfer $saveRequestTransfer, UploadedFile $file = null);
}
