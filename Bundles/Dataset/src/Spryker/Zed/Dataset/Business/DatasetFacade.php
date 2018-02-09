<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

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
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     * @param string $filePath
     *
     * @return void
     */
    public function save(SpyDatasetEntityTransfer $saveRequestTransfer, $filePath)
    {
        $this->getFactory()->createDatasetSaver()->save($saveRequestTransfer, $filePath);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDataset(SpyDatasetEntityTransfer $saveRequestTransfer)
    {
        $this->getFactory()->createDatasetSaver()->save($saveRequestTransfer);
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
    public function getDatasetContent(SpyDatasetEntityTransfer $datasetTransfer)
    {
        return $this->getFactory()->createWriterManager()->getDatasetContentBy($datasetTransfer);
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
    public function getDatasetTransferById($idDataset)
    {
        return $this->getFactory()->createDatasetFinder()->getDatasetTransferById($idDataset);
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
    public function getDatasetTransferByName($datasetName)
    {
        return $this->getFactory()->createDatasetFinder()->getDatasetTransferByName($datasetName);
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
}
