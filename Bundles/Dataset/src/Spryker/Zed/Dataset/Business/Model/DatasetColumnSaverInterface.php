<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer;

interface DatasetColumnSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer $datasetColumnEntityTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColumn
     */
    public function findOrCreate(SpyDatasetColumnEntityTransfer $datasetColumnEntityTransfer);
}
