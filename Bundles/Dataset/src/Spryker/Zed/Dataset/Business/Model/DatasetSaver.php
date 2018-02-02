<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDataset;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DatasetSaver implements DatasetSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface
     */
    protected $datasetFinder;

    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetLocalizedAttributesSaverInterface
     */
    protected $datasetLocalizedAttributesSaver;

    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetRowColValueSaverInterface
     */
    protected $datasetRowColValueSaver;

    /**
     * @var \Spryker\Zed\Dataset\Business\Model\ReaderManagerInterface
     */
    protected $readerManager;

    public function __construct(
        DatasetFinderInterface $datasetFinder,
        DatasetLocalizedAttributesSaverInterface $datasetLocalizedAttributesSaver,
        DatasetRowColValueSaverInterface $datasetRowColValueSaver,
        ReaderManagerInterface $readerManager
    ) {
        $this->datasetFinder = $datasetFinder;
        $this->datasetLocalizedAttributesSaver = $datasetLocalizedAttributesSaver;
        $this->datasetRowColValueSaver = $datasetRowColValueSaver;
        $this->readerManager = $readerManager;
    }

    /**
     * @param null|\Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     *
     * @return bool
     */
    public function save(SpyDatasetEntityTransfer $saveRequestTransfer, UploadedFile $file = null)
    {
        if ($file) {
            $saveRequestTransfer->setSpyDatasetRowColValues($this->readerManager->convertFileToDataTransfers($file));
        }
        if ($this->checkDatasetExists($saveRequestTransfer)) {
            $this->update($saveRequestTransfer);

            return true;
        }
        $this->create($saveRequestTransfer);

        return true;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    protected function saveDataset(SpyDataset $dataset, SpyDatasetEntityTransfer $saveRequestTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($dataset, $saveRequestTransfer) {
            $dataset->fromArray($saveRequestTransfer->toArray());
            if ($saveRequestTransfer->getSpyDatasetRowColValues()->count() && !$dataset->isNew()) {
                $this->datasetRowColValueSaver->removeDatasetRowColValues($dataset);
            }
            $dataset->save();
            $this->datasetLocalizedAttributesSaver->saveDatasetLocalizedAttributes($dataset, $saveRequestTransfer);
            $this->datasetRowColValueSaver->saveDatasetRowColValues($dataset, $saveRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    protected function update(SpyDatasetEntityTransfer $saveRequestTransfer)
    {
        $dataset = $this->datasetFinder->getDatasetyId($saveRequestTransfer->getIdDataset());

        $this->saveDataset($dataset, $saveRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    protected function create(SpyDatasetEntityTransfer $saveRequestTransfer)
    {
        $dataset = new SpyDataset();

        $this->saveDataset($dataset, $saveRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return bool
     */
    protected function checkDatasetExists(SpyDatasetEntityTransfer $saveRequestTransfer)
    {
        $idDataset = $saveRequestTransfer->getIdDataset();
        if ($idDataset == null) {
            return false;
        }
        $dataset = $this->datasetFinder->getDatasetyId($idDataset);

        return $dataset !== null;
    }
}
