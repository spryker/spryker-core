<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Generated\Shared\Transfer\DatasetTransfer;

interface DatasetEntityManagerInterface
{
    /**
     * @param int $idDataset
     * @param bool $isActive
     *
     * @return void
     */
    public function updateIsActiveByIdDataset($idDataset, $isActive): void;

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset): void;

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function saveDataset(DatasetTransfer $datasetTransfer): void;
}
