<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\DatasetTransfer;
use Spryker\Zed\Dataset\Persistence\DatasetEntityManagerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class DatasetSaver implements DatasetSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Dataset\Business\Model\ReaderInterface
     */
    protected $reader;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Dataset\Business\Model\ReaderInterface $reader
     */
    public function __construct(
        DatasetEntityManagerInterface $entityManager,
        ReaderInterface $reader
    ) {
        $this->entityManager = $entityManager;
        $this->reader = $reader;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer|null $filePathTransfer
     *
     * @return void
     */
    public function save(DatasetTransfer $datasetTransfer, ?DatasetFilePathTransfer $filePathTransfer = null)
    {
        if ($filePathTransfer !== null && file_exists($filePathTransfer->getFilePath())) {
            $datasetTransfer->setDatasetRowColumnValues(
                $this->reader->parseFileToDataTransfers($filePathTransfer)
            );
        }
        $this->entityManager->saveDataset($datasetTransfer);
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function activateById($idDataset)
    {
        $this->entityManager->updateIsActiveByIdDataset($idDataset, true);
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function deactivateById($idDataset)
    {
        $this->entityManager->updateIsActiveByIdDataset($idDataset, false);
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset)
    {
        $this->entityManager->delete($idDataset);
    }
}
