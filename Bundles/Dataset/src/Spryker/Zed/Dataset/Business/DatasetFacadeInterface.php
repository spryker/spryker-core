<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Generated\Shared\Transfer\DatasetFilenameTransfer;
use Generated\Shared\Transfer\DatasetFilePathTransfer;
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
    public function delete($idDataset): void;

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
    public function activateById($idDataset): void;

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
    public function deactivateById($idDataset): void;

    /**
     * Specification:
     * - Save dataset entity
     * - Parse file content
     * - Insert data to EAV model
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @return void
     */
    public function save(SpyDatasetEntityTransfer $datasetEntityTransfer, DatasetFilePathTransfer $filePathTransfer): void;

    /**
     * Specification:
     * - Save dataset entity
     * - Save data to EAV model
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    public function saveDataset(SpyDatasetEntityTransfer $datasetEntityTransfer): void;

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
    public function getCsvByDataset(SpyDatasetEntityTransfer $datasetTransfer): string;

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
    public function getDatasetModelById($idDataset): SpyDatasetEntityTransfer;

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
    public function getDatasetModelByName($datasetName): SpyDatasetEntityTransfer;

    /**
     * Specification:
     * - Check if exist dataset with current name.
     *
     * @api
     *
     * @param string $datasetName
     *
     * @return bool
     */
    public function existsDatasetByName($datasetName): bool;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\DatasetFilenameTransfer
     */
    public function getFilenameByDatasetName($datasetName): DatasetFilenameTransfer;
}
