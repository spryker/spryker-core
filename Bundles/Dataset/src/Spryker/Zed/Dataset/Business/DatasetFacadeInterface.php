<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;

interface DatasetFacadeInterface
{
    /**
     * Specification:
     * - Delete Dataset entity.
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset);

    /**
     * Specification:
     * - Activate Dataset entity.
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return void
     */
    public function activateById($idDataset);

    /**
     * Specification:
     * - Deactivate Dataset entity.
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return void
     */
    public function deactivateById($idDataset);

    /**
     * Specification:
     * - Save dataset entity
     * - Parse file content
     * - Insert data to EAV model
     *
     * @api
     *
     * @param null|\Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     * @param string $filePath
     *
     * @return void
     */
    public function save(SpyDatasetEntityTransfer $saveRequestTransfer, $filePath);

    /**
     * Specification:
     * - Save dataset entity
     * - Save data to EAV model
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDataset(SpyDatasetEntityTransfer $saveRequestTransfer);

    /**
     * Specification:
     * - Get content of csv format for dataset.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return string
     */
    public function getDatasetContent(SpyDatasetEntityTransfer $datasetTransfer);

    /**
     * Specification:
     * - Get dataset transfer object with all relations.
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetTransferById($idDataset);

    /**
     * Specification:
     * - Get dataset transfer object with all relations.
     *
     * @api
     *
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetTransferByName($datasetName);

    /**
     * Specification:
     * - Check if exist dataset with current name.
     *
     * @api
     *
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function hasDatasetName($datasetName);
}
