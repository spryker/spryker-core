<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\SpyDatasetEntityTransfer;

interface DatasetSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer|null $filePathTransfer
     *
     * @return void
     */
    public function save(SpyDatasetEntityTransfer $datasetEntityTransfer, ?DatasetFilePathTransfer $filePathTransfer = null);
}
