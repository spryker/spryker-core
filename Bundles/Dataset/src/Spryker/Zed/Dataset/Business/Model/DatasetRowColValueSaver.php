<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Orm\Zed\Dataset\Persistence\SpyDataset;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowColValue;

class DatasetRowColValueSaver implements DatasetRowColValueSaverInterface
{
    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetColSaverInterface
     */
    protected $datasetColSaver;

    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetRowSaverInterface
     */
    protected $datasetRowSaver;


    public function __construct(
        DatasetColSaverInterface $datasetColSaver,
        DatasetRowSaverInterface $datasetRowSaver
    ) {
        $this->datasetColSaver = $datasetColSaver;
        $this->datasetRowSaver = $datasetRowSaver;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     *
     * @return void
     */
    public function removeDatasetRowColValues(SpyDataset $dataset)
    {
        $dataset->getSpyDatasetRowColValues()->delete();
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDatasetRowColValues(SpyDataset $dataset, $saveRequestTransfer)
    {
        $datasetRowColValueTransfers = $saveRequestTransfer->getSpyDatasetRowColValues();
        $datasetRowEntities = [];
        $datasetColEntities = [];

        foreach ($datasetRowColValueTransfers as $datasetRowColValueTransfer) {
            $rowTitle = $datasetRowColValueTransfer->getSpyDatasetRow()->getTitle();
            $colTitle = $datasetRowColValueTransfer->getSpyDatasetCol()->getTitle();
            if (empty($datasetRowValueTransfers[$rowTitle])) {
                $datasetRowEntities[$rowTitle] = $this->datasetRowSaver->getOrCreate(
                    $datasetRowColValueTransfer->getSpyDatasetRow()
                );
            }
            if (empty($datasetColEntities[$colTitle])) {
                $datasetColEntities[$colTitle] = $this->datasetColSaver->getOrCreate(
                    $datasetRowColValueTransfer->getSpyDatasetCol()
                );
            }
            $spyDatasetRowColValue = new SpyDatasetRowColValue();
            $spyDatasetRowColValue->setFkDataset($dataset->getIdDataset());
            $spyDatasetRowColValue->setFkDatasetCol($datasetColEntities[$colTitle]->getIdDatasetCol());
            $spyDatasetRowColValue->setFkDatasetRow($datasetRowEntities[$rowTitle]->getIdDatasetRow());
            $spyDatasetRowColValue->setValue($datasetRowColValueTransfer->getValue());
            $spyDatasetRowColValue->save();

            $dataset->addSpyDatasetRowColValue($spyDatasetRowColValue);
        }
    }
}
