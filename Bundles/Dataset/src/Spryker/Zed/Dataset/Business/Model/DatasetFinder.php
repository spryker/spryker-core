<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDataset;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue;
use Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException;
use Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface;

class DatasetFinder implements DatasetFinderInterface
{
    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface
     */
    protected $datasetQueryContainer;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface $datasetQueryContainer
     */
    public function __construct(DatasetQueryContainerInterface $datasetQueryContainer)
    {
        $this->datasetQueryContainer = $datasetQueryContainer;
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset)
    {
        $this->datasetQueryContainer->queryDatasetById($idDataset)->findOne()->delete();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasDatasetName($name)
    {
        $query = $this->datasetQueryContainer->queryDatasetByName($name);

        return $query->count() > 0;
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function activateById($idDataset)
    {
        $this->updateIsActiveByIdTransaction($idDataset, true);
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function deactivateById($idDataset)
    {
        $this->updateIsActiveByIdTransaction($idDataset, false);
    }

    /**
     * @param int $idDataset
     * @param bool $isActive
     *
     * @return void
     */
    protected function updateIsActiveByIdTransaction($idDataset, $isActive)
    {
        $dataset = $this->getDatasetId($idDataset);
        $dataset->setIsActive($isActive);
        $dataset->save();
    }

    /**
     * @param int $idDataset
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetId($idDataset)
    {
        $dataset = $this->datasetQueryContainer->queryDatasetById($idDataset)->findOne();

        if (!$dataset) {
            throw new DatasetNotFoundException();
        }

        return $dataset;
    }

    /**
     * @param string $datasetName
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetName($datasetName)
    {
        $dataset = $this->datasetQueryContainer->queryDatasetByName($datasetName)->findOne();

        if (!$dataset) {
            throw new DatasetNotFoundException();
        }

        return $dataset;
    }

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetRowByTitle($title)
    {
        return $this->datasetQueryContainer->queryDatasetRowByTitle($title)->findOne();
    }

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetColumnByTitle($title)
    {
        return $this->datasetQueryContainer->queryDatasetColumnByTitle($title)->findOne();
    }

    /**
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetTransferById($idDataset)
    {
        $datasetEntity = $this->datasetQueryContainer->queryDatasetByIdWithRelation($idDataset)->find()->getFirst();
        if ($datasetEntity === null) {
            throw new DatasetNotFoundException();
        }
        $datasetTransfer = $this->getResponseDatasetTransfer($datasetEntity);

        return $datasetTransfer;
    }

    /**
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetTransferByName($datasetName)
    {
        $datasetEntity = $this->datasetQueryContainer->queryDatasetByNameWithRelation($datasetName)->find()->getFirst();
        if ($datasetEntity === null) {
            throw new DatasetNotFoundException();
        }
        $datasetTransfer = $this->getResponseDatasetTransfer($datasetEntity);

        return $datasetTransfer;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    protected function getResponseDatasetTransfer(SpyDataset $datasetEntity)
    {
        $datasetTransfer = new SpyDatasetEntityTransfer();
        $datasetTransfer->fromArray($datasetEntity->toArray(), true);
        $this->appendDatasetRowColTransfers($datasetEntity, $datasetTransfer);
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
            $datasetLocalizedAttributeTransfer = new SpyDatasetLocalizedAttributesEntityTransfer();
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
    protected function appendDatasetRowColTransfers(
        SpyDataset $datasetEntity,
        SpyDatasetEntityTransfer $datasetTransfer
    ) {
        foreach ($datasetEntity->getSpyDatasetRowColumnValues() as $datasetRowColumnValue) {
            $datasetRowColumnValueEntityTransfer = new SpyDatasetRowColumnValueEntityTransfer();
            $datasetRowEntityTransfer = $this->createDatasetRowTransfer($datasetRowColumnValue);
            $datasetColumnEntityTransfer = $this->createDatasetColTransfer($datasetRowColumnValue);
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
    protected function createDatasetColTransfer(SpyDatasetRowColumnValue $datasetRowColumnValueEntity)
    {
        $datasetColumnEntityTransfer = new SpyDatasetColumnEntityTransfer();
        $datasetColumnEntityTransfer->fromArray($datasetRowColumnValueEntity->getSpyDatasetColumn()->toArray());

        return $datasetColumnEntityTransfer;
    }
}
