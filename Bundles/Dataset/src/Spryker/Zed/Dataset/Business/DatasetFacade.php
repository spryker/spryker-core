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
     * @param null|string $filePath
     *
     * @return bool
     */
    public function save(SpyDatasetEntityTransfer $saveRequestTransfer, $filePath = null)
    {
        return $this->getFactory()->createDatasetSaver()->save($saveRequestTransfer, $filePath);
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
     * @param string $nameDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetTransferByName($nameDataset)
    {
        return $this->getFactory()->createDatasetFinder()->getDatasetTransferByName($nameDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $nameDataset
     *
     * @return bool
     */
    public function hasDatasetName($nameDataset)
    {
        return $this->getFactory()->createDatasetFinder()->hasDatasetName($nameDataset);
    }
}
