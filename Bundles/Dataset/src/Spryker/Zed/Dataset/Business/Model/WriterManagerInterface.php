<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;

interface WriterManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return string
     */
    public function getDatasetContentBy(SpyDatasetEntityTransfer $datasetTransfer);
}
