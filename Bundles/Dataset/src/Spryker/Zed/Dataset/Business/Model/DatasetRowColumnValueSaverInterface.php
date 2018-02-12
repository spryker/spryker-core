<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Orm\Zed\Dataset\Persistence\SpyDataset;

interface DatasetRowColumnValueSaverInterface
{
    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     *
     * @return bool
     */
    public function removeDatasetRowColumnValues(SpyDataset $datasetEntity);

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDatasetRowColumnValues(SpyDataset $datasetEntity, $saveRequestTransfer);
}
