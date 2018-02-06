<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDataset;
use Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException;
use Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class DatasetFinder implements DatasetFinderInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface
     */
    protected $datasetQueryContainer;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface $datasetQueryContainer
     */
    public function __construct(
        DatasetQueryContainerInterface $datasetQueryContainer
    ) {
        $this->datasetQueryContainer = $datasetQueryContainer;
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset)
    {
        $this->datasetQueryContainer->queryDatasetById($idDataset)->delete();
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
        $this->handleDatabaseTransaction(function () use ($idDataset) {
            $this->updateIsActiveByIdTransaction($idDataset, true);
        });
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function deactivateById($idDataset)
    {
        $this->handleDatabaseTransaction(function () use ($idDataset) {
            $this->updateIsActiveByIdTransaction($idDataset, false);
        });
    }

    /**
     * @param int $idDataset
     * @param bool $isActive
     *
     * @return void
     */
    protected function updateIsActiveByIdTransaction($idDataset, $isActive)
    {
        $spyDataset = $this->getDatasetId($idDataset);
        $spyDataset->setIsActive($isActive);
        $spyDataset->save();
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
        $spyDataset = $this->datasetQueryContainer->queryDatasetById($idDataset)->findOne();

        if (!$spyDataset) {
            throw new DatasetNotFoundException();
        }

        return $spyDataset;
    }

    /**
     * @param string $nameDataset
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetName($nameDataset)
    {
        $spyDataset = $this->datasetQueryContainer->queryDatasetByName($nameDataset)->findOne();

        if (!$spyDataset) {
            throw new DatasetNotFoundException();
        }

        return $spyDataset;
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
        $spyDatasetEntity = $this->datasetQueryContainer->queryDatasetByIdWithRelation($idDataset)->find()->getFirst();

        $spyDatasetTransfer = $this->getResponseDatasetTransfer($spyDatasetEntity);

        return $spyDatasetTransfer;
    }

    /**
     * @param string $nameDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetTransferByName($nameDataset)
    {
        $spyDatasetEntity = $this->datasetQueryContainer->queryDatasetByNameWithRelation($nameDataset)->find()->getFirst();

        $spyDatasetTransfer = $this->getResponseDatasetTransfer($spyDatasetEntity);

        return $spyDatasetTransfer;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $spyDatasetEntity
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    protected function getResponseDatasetTransfer(SpyDataset $spyDatasetEntity)
    {
        $spyDatasetTransfer = new SpyDatasetEntityTransfer();
        $spyDatasetTransfer->fromArray($spyDatasetEntity->toArray(), true);
        $datasetRowEntityTransfers = [];
        $datasetColumnEntityTransfers = [];
        foreach ($spyDatasetEntity->getSpyDatasetRowColumnValues() as $spyDatasetRowColumnValue) {
            $datasetRowColumnValueEntityTransfer = new SpyDatasetRowColumnValueEntityTransfer();
            if (empty($datasetRowEntityTransfers[$spyDatasetRowColumnValue->getSpyDatasetRow()->getIdDatasetRow()])) {
                $datasetRowEntityTransfer = new SpyDatasetRowEntityTransfer();
                $datasetRowEntityTransfer->fromArray($spyDatasetRowColumnValue->getSpyDatasetRow()->toArray());
                $datasetRowEntityTransfers[$spyDatasetRowColumnValue->getSpyDatasetRow()->getIdDatasetRow()] =
                    $datasetRowEntityTransfer;
            }
            if (empty($datasetColumnEntityTransfers[$spyDatasetRowColumnValue->getSpyDatasetColumn()->getIdDatasetColumn()])) {
                $datasetColumnEntityTransfer = new SpyDatasetColumnEntityTransfer();
                $datasetColumnEntityTransfer->fromArray($spyDatasetRowColumnValue->getSpyDatasetColumn()->toArray());
                $datasetColumnEntityTransfers[$spyDatasetRowColumnValue->getSpyDatasetColumn()->getIdDatasetColumn()] =
                    $datasetColumnEntityTransfer;
            }
            $datasetRowColumnValueEntityTransfer->fromArray($spyDatasetRowColumnValue->toArray());
            $datasetRowColumnValueEntityTransfer->setSpyDatasetRow(
                $datasetRowEntityTransfers[$spyDatasetRowColumnValue->getSpyDatasetRow()->getIdDatasetRow()]
            );
            $datasetRowColumnValueEntityTransfer->setSpyDatasetColumn(
                $datasetColumnEntityTransfers[$spyDatasetRowColumnValue->getSpyDatasetColumn()->getIdDatasetColumn()]
            );
            $spyDatasetTransfer->addSpyDatasetRowColumnValues($datasetRowColumnValueEntityTransfer);
        }

        return $spyDatasetTransfer;
    }
}
