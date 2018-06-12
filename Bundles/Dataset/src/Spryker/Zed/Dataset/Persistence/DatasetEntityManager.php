<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;
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
     * @param int $idDataset
     * @param bool $isActive
     *
     * @return void
     */
    public function updateIsActiveByIdDataset($idDataset, $isActive)
    {
        return $this->handleDatabaseTransaction(function () use ($idDataset, $isActive) {
            $datasetEntity = $this->findDatasetById($idDataset);
            $datasetEntity->setIsActive($isActive);
            $datasetEntity->save();
        });
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset)
    {
        return $this->handleDatabaseTransaction(function () use ($idDataset) {
            $datasetEntity = $this->findDatasetById($idDataset);
            $datasetEntity->delete();
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    public function saveDataset(SpyDatasetEntityTransfer $datasetEntityTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($datasetEntityTransfer) {
            if ($this->checkDatasetExists($datasetEntityTransfer)) {
                $this->update($datasetEntityTransfer);
            } else {
                $this->create($datasetEntityTransfer);
            }
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    protected function update(SpyDatasetEntityTransfer $datasetEntityTransfer)
    {
        $datasetEntity = $this->findDatasetById($datasetEntityTransfer->getIdDataset());

        $this->updateDataset($datasetEntity, $datasetEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    protected function create(SpyDatasetEntityTransfer $datasetEntityTransfer)
    {
        $dataset = new SpyDataset();

        $this->updateDataset($dataset, $datasetEntityTransfer);
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    protected function updateDataset(SpyDataset $datasetEntity, SpyDatasetEntityTransfer $datasetEntityTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($datasetEntity, $datasetEntityTransfer) {
            $datasetEntity->fromArray($datasetEntityTransfer->toArray());
            if ($datasetEntityTransfer->getSpyDatasetRowColumnValues()->count() && !$datasetEntity->isNew()) {
                $this->removeDatasetRowColumnValues($datasetEntity);
            }
            $datasetEntity->save();
            $this->saveDatasetLocalizedAttributes($datasetEntity, $datasetEntityTransfer);
            $this->saveDatasetRowColumnValues($datasetEntity, $datasetEntityTransfer);
        });
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    protected function saveDatasetRowColumnValues(SpyDataset $datasetEntity, $datasetEntityTransfer)
    {
        $datasetRowColumnValueTransfers = $datasetEntityTransfer->getSpyDatasetRowColumnValues();

        foreach ($datasetRowColumnValueTransfers as $datasetRowColumnValueTransfer) {
            $datasetRowUniqueEntity = $this->findOrCreateDatasetRow(
                $datasetRowColumnValueTransfer->getSpyDatasetRow()
            );
            $datasetColumnUniqueEntity = $this->findOrCreateDatasetColumn(
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
     * @param \Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer $datasetColumnEntityTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColumn
     */
    protected function findOrCreateDatasetColumn(SpyDatasetColumnEntityTransfer $datasetColumnEntityTransfer)
    {
        $datasetColumnEntity = $this->getFactory()->createSpyDatasetColumnQuery()->filterByTitle(
            $datasetColumnEntityTransfer->getTitle()
        )->findOne();

        if ($datasetColumnEntity === null) {
            $datasetColumnEntity = new SpyDatasetColumn();
            $datasetColumnEntity->fromArray($datasetColumnEntityTransfer->toArray());
        }
        $datasetColumnEntity->save();

        return $datasetColumnEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetRowEntityTransfer $datasetRowEntityTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRow
     */
    protected function findOrCreateDatasetRow(SpyDatasetRowEntityTransfer $datasetRowEntityTransfer)
    {
        $datasetRowEntity = $this->getFactory()->createSpyDatasetRowQuery()->filterByTitle(
            $datasetRowEntityTransfer->getTitle()
        )->findOne();
        if ($datasetRowEntity === null) {
            $datasetRowEntity = new SpyDatasetRow();
            $datasetRowEntity->fromArray($datasetRowEntityTransfer->toArray());
        }
        $datasetRowEntity->save();

        return $datasetRowEntity;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     *
     * @return void
     */
    protected function removeDatasetRowColumnValues(SpyDataset $datasetEntity)
    {
        $datasetEntity->getSpyDatasetRowColumnValues()->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return bool
     */
    protected function checkDatasetExists(SpyDatasetEntityTransfer $datasetEntityTransfer)
    {
        $idDataset = $datasetEntityTransfer->getIdDataset();
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
    protected function findDatasetById($idDataset)
    {
        return $this->getFactory()->createDatasetQuery()->filterByIdDataset($idDataset)->findOne();
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

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return void
     */
    protected function saveDatasetLocalizedAttributes(
        SpyDataset $datasetEntity,
        SpyDatasetEntityTransfer $datasetEntityTransfer
    ) {
        $localizedAttributes = $datasetEntityTransfer->getSpyDatasetLocalizedAttributess();
        $existingDatasetLocalizedAttributes = $datasetEntity->getSpyDatasetLocalizedAttributess()
            ->toKeyIndex('fkLocale');
        if (empty($existingDatasetLocalizedAttributes)) {
            $this->createLocalizedAttributes($datasetEntity, $localizedAttributes);

            return;
        }
        $this->saveLocalizedAttributes($datasetEntity, $localizedAttributes, $existingDatasetLocalizedAttributes);
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param array $localizedAttributesToSave
     * @param array $existingDatasetLocalizedAttributes
     *
     * @return void
     */
    protected function saveLocalizedAttributes(
        SpyDataset $datasetEntity,
        $localizedAttributesToSave,
        $existingDatasetLocalizedAttributes
    ) {
        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $idLocale = $localizedAttribute->getLocale()->getIdLocale();
            if (!empty($existingDatasetLocalizedAttributes[$idLocale])) {
                $this->updateLocalizedAttribute($existingDatasetLocalizedAttributes[$idLocale], $localizedAttribute);
                continue;
            }
            $this->createLocalizedAttributes($datasetEntity, [$localizedAttribute]);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param array $localizedAttributesToSave
     *
     * @return void
     */
    protected function createLocalizedAttributes(SpyDataset $datasetEntity, $localizedAttributesToSave)
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
     * @param \Generated\Shared\Transfer\SpyDatasetLocalizedAttributesEntityTransfer $newAttribute
     *
     * @return void
     */
    protected function updateLocalizedAttribute(
        SpyDatasetLocalizedAttributes $existingAttribute,
        SpyDatasetLocalizedAttributesEntityTransfer $newAttribute
    ) {
        $existingAttribute->fromArray($newAttribute->toArray());
        $existingAttribute->save();
    }
}
