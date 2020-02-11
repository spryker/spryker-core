<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Generated\Shared\Transfer\DatasetFilenameTransfer;
use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\DatasetTransfer;

interface DatasetFacadeInterface
{
    /**
     * Specification:
     * - Delete Dataset entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function delete(DatasetTransfer $datasetTransfer): void;

    /**
     * Specification:
     * - Activate or deactivate Dataset entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function activateDataset(DatasetTransfer $datasetTransfer): void;

    /**
     * Specification:
     * - Save dataset entity
     * - Parse file content
     * - Insert data to EAV model
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @return void
     */
    public function save(DatasetTransfer $datasetTransfer, DatasetFilePathTransfer $filePathTransfer): void;

    /**
     * Specification:
     * - Save dataset entity
     * - Save data to EAV model
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function saveDataset(DatasetTransfer $datasetTransfer): void;

    /**
     * Specification:
     * - Get content of csv format for dataset.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return string
     */
    public function getCsvByDataset(DatasetTransfer $datasetTransfer): string;

    /**
     * Specification:
     * - Get dataset transfer object with all relations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelById(DatasetTransfer $datasetTransfer): DatasetTransfer;

    /**
     * Specification:
     * - Get dataset transfer object with all relations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelByName(DatasetTransfer $datasetTransfer): DatasetTransfer;

    /**
     * Specification:
     * - Check if exist dataset with current name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return bool
     */
    public function existsDatasetByName(DatasetTransfer $datasetTransfer): bool;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetFilenameTransfer $datasetFilenameTransfer
     *
     * @return \Generated\Shared\Transfer\DatasetFilenameTransfer
     */
    public function getFilenameByDatasetName(DatasetFilenameTransfer $datasetFilenameTransfer): DatasetFilenameTransfer;
}
