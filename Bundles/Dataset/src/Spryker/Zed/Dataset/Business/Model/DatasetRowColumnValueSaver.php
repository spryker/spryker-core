<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Orm\Zed\Dataset\Persistence\SpyDataset;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue;

class DatasetRowColumnValueSaver implements DatasetRowColumnValueSaverInterface
{
    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetColumnSaverInterface
     */
    protected $datasetColumnSaver;

    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetRowSaverInterface
     */
    protected $datasetRowSaver;

    /**
     * @param \Spryker\Zed\Dataset\Business\Model\DatasetColumnSaverInterface $datasetColumnSaver
     * @param \Spryker\Zed\Dataset\Business\Model\DatasetRowSaverInterface $datasetRowSaver
     */
    public function __construct(
        DatasetColumnSaverInterface $datasetColumnSaver,
        DatasetRowSaverInterface $datasetRowSaver
    ) {
        $this->datasetColumnSaver = $datasetColumnSaver;
        $this->datasetRowSaver = $datasetRowSaver;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     *
     * @return void
     */
    public function removeDatasetRowColumnValues(SpyDataset $dataset)
    {
        $dataset->getSpyDatasetRowColumnValues()->delete();
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDatasetRowColumnValues(SpyDataset $dataset, $saveRequestTransfer)
    {
        $datasetRowColumnValueTransfers = $saveRequestTransfer->getSpyDatasetRowColumnValues();
        $datasetRowUniqueEntities = [];
        $datasetColumnUniqueEntities = [];

        foreach ($datasetRowColumnValueTransfers as $datasetRowColumnValueTransfer) {
            $rowTitle = $datasetRowColumnValueTransfer->getSpyDatasetRow()->getTitle();
            if (empty($datasetRowUniqueEntities[$rowTitle])) {
                $datasetRowUniqueEntities[$rowTitle] = $this->datasetRowSaver->findOrCreate(
                    $datasetRowColumnValueTransfer->getSpyDatasetRow()
                );
            }
            $columnTitle = $datasetRowColumnValueTransfer->getSpyDatasetColumn()->getTitle();
            if (empty($datasetColumnUniqueEntities[$columnTitle])) {
                $datasetColumnUniqueEntities[$columnTitle] = $this->datasetColumnSaver->findOrCreate(
                    $datasetRowColumnValueTransfer->getSpyDatasetColumn()
                );
            }
            $spyDatasetRowColumnValue = $this->createDatasetRowColumnValue(
                $dataset->getIdDataset(),
                $datasetColumnUniqueEntities[$columnTitle]->getIdDatasetColumn(),
                $datasetRowUniqueEntities[$rowTitle]->getIdDatasetRow(),
                $datasetRowColumnValueTransfer->getValue()
            );
            $dataset->addSpyDatasetRowColumnValue($spyDatasetRowColumnValue);
        }
    }

    /**
     * @param int $IdDataset
     * @param int $idDatasetColumn
     * @param int $idDatasetRow
     * @param string $value
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue
     */

    protected function createDatasetRowColumnValue(
        $IdDataset,
        $idDatasetColumn,
        $idDatasetRow,
        $value
    ) {
        $spyDatasetRowColumnValue = new SpyDatasetRowColumnValue();
        $spyDatasetRowColumnValue->setFkDataset($IdDataset);
        $spyDatasetRowColumnValue->setFkDatasetColumn($idDatasetColumn);
        $spyDatasetRowColumnValue->setFkDatasetRow($idDatasetRow);
        $spyDatasetRowColumnValue->setValue($value);
        $spyDatasetRowColumnValue->save();

        return $spyDatasetRowColumnValue;
    }
}
