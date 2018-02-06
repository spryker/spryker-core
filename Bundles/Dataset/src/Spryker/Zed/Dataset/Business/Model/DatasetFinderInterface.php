<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

interface DatasetFinderInterface
{
    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset);

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function activateById($idDataset);

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function deactivateById($idDataset);

    /**
     * @param int $idDataset
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetId($idDataset);

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetRowByTitle($title);

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetColumnByTitle($title);

    /**
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetTransferById($idDataset);

    /**
     * @param string $nameDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetTransferByName($nameDataset);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasDatasetName($name);
}
