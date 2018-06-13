<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Generated\Shared\Transfer\DatasetFilenameTransfer;
use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\DatasetTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetBusinessFactory getFactory()
 */
class DatasetFacade extends AbstractFacade implements DatasetFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset): void
    {
        $this->getFactory()->createDatasetSaver()->delete($idDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return void
     */
    public function activateById($idDataset): void
    {
        $this->getFactory()->createDatasetSaver()->activateById($idDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return void
     */
    public function deactivateById($idDataset): void
    {
        $this->getFactory()->createDatasetSaver()->deactivateById($idDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @return void
     */
    public function save(DatasetTransfer $datasetTransfer, DatasetFilePathTransfer $filePathTransfer): void
    {
        $this->getFactory()->createDatasetSaver()->save($datasetTransfer, $filePathTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function saveDataset(DatasetTransfer $datasetTransfer): void
    {
        $this->getFactory()->createDatasetSaver()->save($datasetTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return string
     */
    public function getCsvByDataset(DatasetTransfer $datasetTransfer): string
    {
        return $this->getFactory()->createWriter()->getCsvByDataset($datasetTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelById($idDataset): DatasetTransfer
    {
        return $this->getFactory()->createDatasetFinder()->getDatasetModelById($idDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelByName($datasetName): DatasetTransfer
    {
        return $this->getFactory()->createDatasetFinder()->getDatasetModelByName($datasetName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $datasetName
     *
     * @return bool
     */
    public function existsDatasetByName($datasetName): bool
    {
        return $this->getFactory()->createDatasetFinder()->existsDatasetByName($datasetName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\DatasetFilenameTransfer
     */
    public function getFilenameByDatasetName($datasetName): DatasetFilenameTransfer
    {
        return $this->getFactory()->createResolverPath()->getFilenameByDatasetName($datasetName);
    }
}
