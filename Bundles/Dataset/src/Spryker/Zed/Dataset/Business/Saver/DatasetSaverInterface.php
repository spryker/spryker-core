<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Saver;

use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\DatasetTransfer;

interface DatasetSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer|null $filePathTransfer
     *
     * @return void
     */
    public function save(DatasetTransfer $datasetTransfer, ?DatasetFilePathTransfer $filePathTransfer = null): void;

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function activateById($idDataset): void;

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function deactivateById($idDataset): void;

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset): void;
}
