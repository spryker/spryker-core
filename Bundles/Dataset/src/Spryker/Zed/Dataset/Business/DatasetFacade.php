<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
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
    public function delete($idDataset)
    {
        $this->getFactory()->createDatasetFinder()->delete($idDataset);
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
    public function activateById($idDataset)
    {
        $this->getFactory()->createDatasetFinder()->activateById($idDataset);
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
    public function deactivateById($idDataset)
    {
        $this->getFactory()->createDatasetFinder()->deactivateById($idDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @return void
     */
    public function save(SpyDatasetEntityTransfer $datasetEntityTransfer, DatasetFilePathTransfer $filePathTransfer)
    {
        $this->getFactory()->createDatasetSaver()->save($datasetEntityTransfer, $filePathTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    public function saveDataset(SpyDatasetEntityTransfer $datasetEntityTransfer)
    {
        $this->getFactory()->createDatasetSaver()->save($datasetEntityTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return string
     */
    public function exportToCsv(SpyDatasetEntityTransfer $datasetTransfer)
    {
        return $this->getFactory()->createWriter()->exportToCsv($datasetTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetModelById($idDataset)
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
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetModelByName($datasetName)
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
    public function hasDatasetName($datasetName)
    {
        return $this->getFactory()->createDatasetFinder()->hasDatasetName($datasetName);
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
    public function getFilenameByDatasetName($datasetName)
    {
        return $this->getFactory()->createDownloader()->getFilenameByDatasetName($datasetName);
    }
}
