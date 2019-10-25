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
 * @method \Spryker\Zed\Dataset\Persistence\DatasetEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface getRepository()
 */
class DatasetFacade extends AbstractFacade implements DatasetFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function delete(DatasetTransfer $datasetTransfer): void
    {
        $this->getFactory()->createDatasetSaver()->delete($datasetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function activateDataset(DatasetTransfer $datasetTransfer): void
    {
        $this->getFactory()->createDatasetSaver()->activateDataset($datasetTransfer);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelById(DatasetTransfer $datasetTransfer): DatasetTransfer
    {
        return $this->getFactory()->createDatasetFinder()->getDatasetModelById($datasetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelByName(DatasetTransfer $datasetTransfer): DatasetTransfer
    {
        return $this->getFactory()->createDatasetFinder()->getDatasetModelByName($datasetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return bool
     */
    public function existsDatasetByName(DatasetTransfer $datasetTransfer): bool
    {
        return $this->getFactory()->createDatasetFinder()->existsDatasetByName($datasetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DatasetFilenameTransfer $datasetFilenameTransfer
     *
     * @return \Generated\Shared\Transfer\DatasetFilenameTransfer
     */
    public function getFilenameByDatasetName(DatasetFilenameTransfer $datasetFilenameTransfer): DatasetFilenameTransfer
    {
        return $this->getFactory()->createResolverPath()->getFilenameByDatasetName($datasetFilenameTransfer);
    }
}
