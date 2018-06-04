<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;

interface DatasetEntityManagerInterface
{
    /**
     * @param int $idDataset
     * @param bool $isActive
     *
     * @return void
     */
    public function updateIsActiveByIdDataset($idDataset, $isActive);

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset);

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    public function saveDataset(SpyDatasetEntityTransfer $datasetEntityTransfer);
}
