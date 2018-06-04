<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

interface DatasetRepositoryInterface
{
    /**
     * @param int $idDataset
     *
     * @return bool
     */
    public function hasDatasetData($idDataset);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasDatasetName($name);

    /**
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetByIdWithRelation($idDataset);

    /**
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetByNameWithRelation($datasetName);
}
