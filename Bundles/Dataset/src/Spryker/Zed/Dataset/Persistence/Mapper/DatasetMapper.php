<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence\Mapper;

use Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;
use Generated\Shared\Transfer\SpyLocaleEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDataset;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue;

class DatasetMapper implements DatasetMapperInterface
{
    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getResponseDatasetTransfer(SpyDataset $datasetEntity)
    {
        $datasetTransfer = new SpyDatasetEntityTransfer();
        $datasetTransfer->fromArray($datasetEntity->toArray(), true);
        $this->appendDatasetRowColumnTransfers($datasetEntity, $datasetTransfer);
        $this->appendDatasetLocalizedAttributesTransfers($datasetEntity, $datasetTransfer);

        return $datasetTransfer;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return void
     */
    protected function appendDatasetLocalizedAttributesTransfers(
        SpyDataset $datasetEntity,
        SpyDatasetEntityTransfer $datasetTransfer
    ) {
        foreach ($datasetEntity->getSpyDatasetLocalizedAttributess() as $datasetLocalizedAttribute) {
            $localEntityTransfer = new SpyLocaleEntityTransfer();
            $localEntityTransfer->fromArray($datasetLocalizedAttribute->getLocale()->toArray());
            $datasetLocalizedAttributeTransfer = new SpyDatasetLocalizedAttributesEntityTransfer();
            $datasetLocalizedAttributeTransfer->setLocale($localEntityTransfer);
            $datasetLocalizedAttributeTransfer->fromArray($datasetLocalizedAttribute->toArray());
            $datasetTransfer->addSpyDatasetLocalizedAttributess($datasetLocalizedAttributeTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return void
     */
    protected function appendDatasetRowColumnTransfers(
        SpyDataset $datasetEntity,
        SpyDatasetEntityTransfer $datasetTransfer
    ) {
        foreach ($datasetEntity->getSpyDatasetRowColumnValues() as $datasetRowColumnValue) {
            $datasetRowColumnValueEntityTransfer = new SpyDatasetRowColumnValueEntityTransfer();
            $datasetRowEntityTransfer = $this->createDatasetRowTransfer($datasetRowColumnValue);
            $datasetColumnEntityTransfer = $this->createDatasetColumnTransfer($datasetRowColumnValue);
            $datasetRowColumnValueEntityTransfer->fromArray($datasetRowColumnValue->toArray());
            $datasetRowColumnValueEntityTransfer->setSpyDatasetRow($datasetRowEntityTransfer);
            $datasetRowColumnValueEntityTransfer->setSpyDatasetColumn($datasetColumnEntityTransfer);
            $datasetTransfer->addSpyDatasetRowColumnValues($datasetRowColumnValueEntityTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue $datasetRowColumnValueEntity
     *
     * @return \Generated\Shared\Transfer\SpyDatasetRowEntityTransfer
     */
    protected function createDatasetRowTransfer(SpyDatasetRowColumnValue $datasetRowColumnValueEntity)
    {
        $datasetRowEntityTransfer = new SpyDatasetRowEntityTransfer();
        $datasetRowEntityTransfer->fromArray($datasetRowColumnValueEntity->getSpyDatasetRow()->toArray());

        return $datasetRowEntityTransfer;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue $datasetRowColumnValueEntity
     *
     * @return \Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer
     */
    protected function createDatasetColumnTransfer(SpyDatasetRowColumnValue $datasetRowColumnValueEntity)
    {
        $datasetColumnEntityTransfer = new SpyDatasetColumnEntityTransfer();
        $datasetColumnEntityTransfer->fromArray($datasetRowColumnValueEntity->getSpyDatasetColumn()->toArray());

        return $datasetColumnEntityTransfer;
    }
}
