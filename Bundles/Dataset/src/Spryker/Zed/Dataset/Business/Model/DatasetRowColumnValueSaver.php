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
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     *
     * @return void
     */
    public function removeDatasetRowColumnValues(SpyDataset $datasetEntity)
    {
        $datasetEntity->getSpyDatasetRowColumnValues()->delete();
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDatasetRowColumnValues(SpyDataset $datasetEntity, $saveRequestTransfer)
    {
        $datasetRowColumnValueTransfers = $saveRequestTransfer->getSpyDatasetRowColumnValues();

        foreach ($datasetRowColumnValueTransfers as $datasetRowColumnValueTransfer) {
            $datasetRowUniqueEntity = $this->datasetRowSaver->findOrCreate(
                $datasetRowColumnValueTransfer->getSpyDatasetRow()
            );
            $datasetColumnUniqueEntity = $this->datasetColumnSaver->findOrCreate(
                $datasetRowColumnValueTransfer->getSpyDatasetColumn()
            );
            $datasetRowColumnValue = $this->createDatasetRowColumnValue(
                $datasetEntity->getIdDataset(),
                $datasetColumnUniqueEntity->getIdDatasetColumn(),
                $datasetRowUniqueEntity->getIdDatasetRow(),
                $datasetRowColumnValueTransfer->getValue()
            );
            $datasetEntity->addSpyDatasetRowColumnValue($datasetRowColumnValue);
        }
    }

    /**
     * @param int $idDataset
     * @param int $idDatasetColumn
     * @param int $idDatasetRow
     * @param string $value
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue
     */
    protected function createDatasetRowColumnValue($idDataset, $idDatasetColumn, $idDatasetRow, $value)
    {
        $datasetRowColumnValue = new SpyDatasetRowColumnValue();
        $datasetRowColumnValue->setFkDataset($idDataset);
        $datasetRowColumnValue->setFkDatasetColumn($idDatasetColumn);
        $datasetRowColumnValue->setFkDatasetRow($idDatasetRow);
        $datasetRowColumnValue->setValue($value);
        $datasetRowColumnValue->save();

        return $datasetRowColumnValue;
    }
}
