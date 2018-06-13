<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

interface DatasetFinderInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsDatasetByName($name);

    /**
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelById($idDataset);

    /**
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelByName($datasetName);
}
