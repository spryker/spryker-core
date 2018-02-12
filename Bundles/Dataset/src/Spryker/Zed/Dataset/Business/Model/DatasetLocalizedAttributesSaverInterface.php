<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDataset;

interface DatasetLocalizedAttributesSaverInterface
{
    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDatasetLocalizedAttributes(
        SpyDataset $datasetEntity,
        SpyDatasetEntityTransfer $saveRequestTransfer
    );
}
