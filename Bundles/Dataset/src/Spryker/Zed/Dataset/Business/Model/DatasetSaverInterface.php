<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;

interface DatasetSaverInterface
{
    /**
     * @param null|\Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     * @param string|null $filePath
     *
     * @return void
     */
    public function save(SpyDatasetEntityTransfer $saveRequestTransfer, $filePath = null);
}
