<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\DatasetColumnTransfer;
use Generated\Shared\Transfer\DatasetLocalizedAttributeTransfer;
use Generated\Shared\Transfer\DatasetRowTransfer;
use Generated\Shared\Transfer\DatasetTransfer;
use Orm\Zed\Dataset\Persistence\SpyDataset;
use Orm\Zed\Dataset\Persistence\SpyDatasetColumn;
use Orm\Zed\Dataset\Persistence\SpyDatasetLocalizedAttributes;
use Orm\Zed\Dataset\Persistence\SpyDatasetRow;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\Dataset\Persistence\DatasetPersistenceFactory getFactory()
 */
class DatasetEntityManager extends AbstractEntityManager implements DatasetEntityManagerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function updateIsActiveDataset(DatasetTransfer $datasetTransfer): void
    {
        $this->handleDatabaseTransaction(function () use ($datasetTransfer): void {
            $datasetEntity = $this->findDatasetById($datasetTransfer->requireIdDataset()->getIdDataset());
            $datasetEntity->setIsActive($datasetTransfer->requireIsActive()->getIsActive());
            $datasetEntity->save();
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function delete(DatasetTransfer $datasetTransfer): void
    {
        $this->handleDatabaseTransaction(function () use ($datasetTransfer): void {
            $datasetEntity = $this->findDatasetById($datasetTransfer->requireIdDataset()->getIdDataset());
            $datasetEntity->delete();
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    public function saveDataset(DatasetTransfer $datasetTransfer): void
    {
        $this->handleDatabaseTransaction(function () use ($datasetTransfer): void {
            if ($this->checkDatasetExists($datasetTransfer)) {
                $this->update($datasetTransfer);
            } else {
                $this->create($datasetTransfer);
            }
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function update(DatasetTransfer $datasetTransfer): void
    {
        $datasetEntity = $this->findDatasetById($datasetTransfer->getIdDataset());

        $this->updateDataset($datasetEntity, $datasetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function create(DatasetTransfer $datasetTransfer): void
    {
        $this->handleDatabaseTransaction(function () use ($datasetTransfer): void {
            $datasetEntity = new SpyDataset();
            $datasetEntity->fromArray($datasetTransfer->toArray());
            $datasetEntity->save();
            $this->saveDatasetLocalizedAttributes($datasetEntity, $datasetTransfer);
            $this->saveDatasetRowColumnValues($datasetEntity, $datasetTransfer);
            $datasetEntity->save();
        });
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function updateDataset(SpyDataset $datasetEntity, DatasetTransfer $datasetTransfer): void
    {
        $this->handleDatabaseTransaction(function () use ($datasetEntity, $datasetTransfer): void {
            $datasetEntity->fromArray($datasetTransfer->toArray());
            if ($datasetTransfer->getDatasetRowColumnValues()->count()) {
                $this->removeDatasetRowColumnValues($datasetEntity);
            }
            $datasetEntity->save();
            $this->saveDatasetLocalizedAttributes($datasetEntity, $datasetTransfer);
            $this->saveDatasetRowColumnValues($datasetEntity, $datasetTransfer);
            $datasetEntity->save();
        });
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function saveDatasetRowColumnValues(SpyDataset $datasetEntity, DatasetTransfer $datasetTransfer): void
    {
        $datasetRowColumnValueTransfers = $datasetTransfer->getDatasetRowColumnValues();

        foreach ($datasetRowColumnValueTransfers as $datasetRowColumnValueTransfer) {
            $datasetRowUniqueEntity = $this->findOrCreateDatasetRow(
                $datasetRowColumnValueTransfer->getDatasetRow(),
            );
            $datasetColumnUniqueEntity = $this->findOrCreateDatasetColumn(
                $datasetRowColumnValueTransfer->getDatasetColumn(),
            );
            $datasetRowColumnValue = $this->createDatasetRowColumnValue(
                $datasetEntity->getIdDataset(),
                $datasetColumnUniqueEntity->getIdDatasetColumn(),
                $datasetRowUniqueEntity->getIdDatasetRow(),
                $datasetRowColumnValueTransfer->getValue(),
            );
            $datasetEntity->addSpyDatasetRowColumnValue($datasetRowColumnValue);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetColumnTransfer $datasetColumnTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColumn
     */
    protected function findOrCreateDatasetColumn(DatasetColumnTransfer $datasetColumnTransfer): SpyDatasetColumn
    {
        $datasetColumnEntity = $this->getFactory()->createSpyDatasetColumnQuery()->filterByTitle(
            $datasetColumnTransfer->getTitle(),
        )->findOne();

        if ($datasetColumnEntity === null) {
            $datasetColumnEntity = new SpyDatasetColumn();
            $datasetColumnEntity->fromArray($datasetColumnTransfer->toArray());
        }
        $datasetColumnEntity->save();

        return $datasetColumnEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetRowTransfer $datasetRowTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRow
     */
    protected function findOrCreateDatasetRow(DatasetRowTransfer $datasetRowTransfer): SpyDatasetRow
    {
        $datasetRowEntity = $this->getFactory()->createSpyDatasetRowQuery()->filterByTitle(
            $datasetRowTransfer->getTitle(),
        )->findOne();
        if ($datasetRowEntity === null) {
            $datasetRowEntity = new SpyDatasetRow();
            $datasetRowEntity->fromArray($datasetRowTransfer->toArray());
        }
        $datasetRowEntity->save();

        return $datasetRowEntity;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     *
     * @return void
     */
    protected function removeDatasetRowColumnValues(SpyDataset $datasetEntity): void
    {
        $datasetEntity->getSpyDatasetRowColumnValues()->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return bool
     */
    protected function checkDatasetExists(DatasetTransfer $datasetTransfer): bool
    {
        $idDataset = $datasetTransfer->getIdDataset();
        if ($idDataset === null) {
            return false;
        }
        $datasetEntity = $this->findDatasetById($idDataset);

        return $datasetEntity !== null;
    }

    /**
     * @param int $idDataset
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    protected function findDatasetById(int $idDataset): SpyDataset
    {
        /** @var \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity */
        $datasetEntity = $this->getFactory()->createDatasetQuery()->filterByIdDataset($idDataset)->findOne();

        return $datasetEntity;
    }

    /**
     * @param int $idDataset
     * @param int $idDatasetColumn
     * @param int $idDatasetRow
     * @param string $value
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValue
     */
    protected function createDatasetRowColumnValue(int $idDataset, int $idDatasetColumn, int $idDatasetRow, string $value): SpyDatasetRowColumnValue
    {
        $datasetRowColumnValue = new SpyDatasetRowColumnValue();
        $datasetRowColumnValue->setFkDataset($idDataset);
        $datasetRowColumnValue->setFkDatasetColumn($idDatasetColumn);
        $datasetRowColumnValue->setFkDatasetRow($idDatasetRow);
        $datasetRowColumnValue->setValue($value);
        $datasetRowColumnValue->save();

        return $datasetRowColumnValue;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function saveDatasetLocalizedAttributes(
        SpyDataset $datasetEntity,
        DatasetTransfer $datasetTransfer
    ): void {
        $localizedAttributes = $datasetTransfer->getDatasetLocalizedAttributes();
        $existingDatasetLocalizedAttributes = $datasetEntity->getSpyDatasetLocalizedAttributess()
            ->toKeyIndex('fkLocale');
        if (!$existingDatasetLocalizedAttributes) {
            $this->createLocalizedAttributes($datasetEntity, $localizedAttributes);

            return;
        }
        $this->saveLocalizedAttributes($datasetEntity, $localizedAttributes, $existingDatasetLocalizedAttributes);
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DatasetLocalizedAttributeTransfer> $localizedAttributesToSave
     * @param array $existingDatasetLocalizedAttributes
     *
     * @return void
     */
    protected function saveLocalizedAttributes(
        SpyDataset $datasetEntity,
        ArrayObject $localizedAttributesToSave,
        array $existingDatasetLocalizedAttributes
    ): void {
        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $idLocale = $localizedAttribute->getLocale()->getIdLocale();
            if (!empty($existingDatasetLocalizedAttributes[$idLocale])) {
                $this->updateLocalizedAttribute($existingDatasetLocalizedAttributes[$idLocale], $localizedAttribute);

                continue;
            }

            /** @var \ArrayObject<int, \Generated\Shared\Transfer\DatasetLocalizedAttributeTransfer> $localizedAttributesToSave */
            $localizedAttributesToSave = new ArrayObject([$localizedAttribute]);
            $this->createLocalizedAttributes($datasetEntity, $localizedAttributesToSave);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DatasetLocalizedAttributeTransfer> $localizedAttributesToSave
     *
     * @return void
     */
    protected function createLocalizedAttributes(SpyDataset $datasetEntity, ArrayObject $localizedAttributesToSave): void
    {
        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $localizedAttributeEntity = new SpyDatasetLocalizedAttributes();
            $localizedAttributeEntity->fromArray($localizedAttribute->toArray());
            $localizedAttributeEntity->setFkLocale($localizedAttribute->getLocale()->getIdLocale());
            $localizedAttributeEntity->setFkDataset($datasetEntity->getIdDataset());
            $localizedAttributeEntity->save();
            $datasetEntity->addSpyDatasetLocalizedAttributes($localizedAttributeEntity);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDatasetLocalizedAttributes $existingAttribute
     * @param \Generated\Shared\Transfer\DatasetLocalizedAttributeTransfer $newAttribute
     *
     * @return void
     */
    protected function updateLocalizedAttribute(
        SpyDatasetLocalizedAttributes $existingAttribute,
        DatasetLocalizedAttributeTransfer $newAttribute
    ): void {
        $existingAttribute->fromArray($newAttribute->toArray());
        $existingAttribute->save();
    }
}
