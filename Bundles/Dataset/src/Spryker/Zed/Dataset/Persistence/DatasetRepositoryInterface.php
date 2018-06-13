<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Generated\Shared\Transfer\DatasetTransfer;

interface DatasetRepositoryInterface
{
    /**
     * @param int $idDataset
     *
     * @return bool
     */
    public function existsDatasetById($idDataset): bool;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsDatasetByName($name): bool;

    /**
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetByIdWithRelation($idDataset): DatasetTransfer;

    /**
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetByNameWithRelation($datasetName): DatasetTransfer;
}
