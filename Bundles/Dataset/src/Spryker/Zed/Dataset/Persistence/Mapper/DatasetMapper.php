<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence\Mapper;

use Generated\Shared\Transfer\DatasetColumnTransfer;
use Generated\Shared\Transfer\DatasetLocalizedAttributeTransfer;
use Generated\Shared\Transfer\DatasetRowColumnValueTransfer;
use Generated\Shared\Transfer\DatasetRowTransfer;
use Generated\Shared\Transfer\DatasetTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Dataset\Persistence\Map\SpyDatasetRowColumnValueTableMap;
use Orm\Zed\Dataset\Persistence\SpyDataset;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue;
use Propel\Runtime\ActiveQuery\Criteria;

class DatasetMapper implements DatasetMapperInterface
{
    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getResponseDatasetTransfer(SpyDataset $datasetEntity): DatasetTransfer
    {
        $datasetTransfer = new DatasetTransfer();
        $datasetTransfer->fromArray($datasetEntity->toArray(), true);
        $this->appendDatasetRowColumnTransfers($datasetEntity, $datasetTransfer);
        $this->appendDatasetLocalizedAttributesTransfers($datasetEntity, $datasetTransfer);

        return $datasetTransfer;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function appendDatasetLocalizedAttributesTransfers(
        SpyDataset $datasetEntity,
        DatasetTransfer $datasetTransfer
    ): void {
        foreach ($datasetEntity->getSpyDatasetLocalizedAttributess() as $datasetLocalizedAttribute) {
            $localTransfer = new LocaleTransfer();
            $localTransfer->fromArray($datasetLocalizedAttribute->getLocale()->toArray());
            $datasetLocalizedAttributeTransfer = new DatasetLocalizedAttributeTransfer();
            $datasetLocalizedAttributeTransfer->setLocale($localTransfer);
            $datasetLocalizedAttributeTransfer->fromArray($datasetLocalizedAttribute->toArray(), true);
            $datasetTransfer->addDatasetLocalizedAttribute($datasetLocalizedAttributeTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function appendDatasetRowColumnTransfers(
        SpyDataset $datasetEntity,
        DatasetTransfer $datasetTransfer
    ): void {
        foreach ($datasetEntity->getSpyDatasetRowColumnValues($this->getSpyDatasetRowColumnValuesCriteria()) as $datasetRowColumnValue) {
            $datasetRowColumnValueTransfer = new DatasetRowColumnValueTransfer();
            $datasetRowTransfer = $this->createDatasetRowTransfer($datasetRowColumnValue);
            $datasetColumnTransfer = $this->createDatasetColumnTransfer($datasetRowColumnValue);
            $datasetRowColumnValueTransfer->fromArray($datasetRowColumnValue->toArray(), true);
            $datasetRowColumnValueTransfer->setDatasetRow($datasetRowTransfer);
            $datasetRowColumnValueTransfer->setDatasetColumn($datasetColumnTransfer);
            $datasetTransfer->addDatasetRowColumnValue($datasetRowColumnValueTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue $datasetRowColumnValueEntity
     *
     * @return \Generated\Shared\Transfer\DatasetRowTransfer
     */
    protected function createDatasetRowTransfer(SpyDatasetRowColumnValue $datasetRowColumnValueEntity): DatasetRowTransfer
    {
        $datasetRowTransfer = new DatasetRowTransfer();
        $datasetRowTransfer->fromArray($datasetRowColumnValueEntity->getSpyDatasetRow()->toArray());

        return $datasetRowTransfer;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue $datasetRowColumnValueEntity
     *
     * @return \Generated\Shared\Transfer\DatasetColumnTransfer
     */
    protected function createDatasetColumnTransfer(SpyDatasetRowColumnValue $datasetRowColumnValueEntity): DatasetColumnTransfer
    {
        $datasetColumnTransfer = new DatasetColumnTransfer();
        $datasetColumnTransfer->fromArray($datasetRowColumnValueEntity->getSpyDatasetColumn()->toArray());

        return $datasetColumnTransfer;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function getSpyDatasetRowColumnValuesCriteria(): Criteria
    {
        return (new Criteria())->addAscendingOrderByColumn(SpyDatasetRowColumnValueTableMap::COL_ID_ROW_COLUMN_VALUE);
    }
}
