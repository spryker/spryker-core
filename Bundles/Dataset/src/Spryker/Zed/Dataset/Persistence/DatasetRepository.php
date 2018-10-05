<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Generated\Shared\Transfer\DatasetTransfer;
use Orm\Zed\Dataset\Persistence\SpyDatasetQuery;
use Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Dataset\Persistence\DatasetPersistenceFactory getFactory()
 */
class DatasetRepository extends AbstractRepository implements DatasetRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return bool
     */
    public function existsDatasetById(DatasetTransfer $datasetTransfer): bool
    {
        $count = $this->getFactory()->createSpyDatasetRowColumnValueQuery()->filterByFkDataset($datasetTransfer->getIdDataset())->count();

        return ($count > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return bool
     */
    public function existsDatasetByName(DatasetTransfer $datasetTransfer): bool
    {
        return ($this->getFactory()->createDatasetQuery()->filterByName($datasetTransfer->getName())->count() > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetByIdWithRelation(DatasetTransfer $datasetTransfer): DatasetTransfer
    {
        $datasetEntity = $this->joinDatasetRelations(
            $this->getFactory()->createDatasetQuery()->filterByIdDataset($datasetTransfer->requireIdDataset()->getIdDataset())
        )->find()->getFirst();

        if (!$datasetEntity) {
            throw new DatasetNotFoundException();
        }

        return $this->getFactory()->createDatasetMapper()->getResponseDatasetTransfer($datasetEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetByNameWithRelation(DatasetTransfer $datasetTransfer): DatasetTransfer
    {
        $datasetEntity = $this->joinDatasetRelations(
            $this->getFactory()->createDatasetQuery()->filterByName($datasetTransfer->requireName()->getName())
        )->find()->getFirst();

        if (!$datasetEntity) {
            throw new DatasetNotFoundException();
        }

        return $this->getFactory()->createDatasetMapper()->getResponseDatasetTransfer($datasetEntity);
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDatasetQuery $datasetQuery
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    protected function joinDatasetRelations(SpyDatasetQuery $datasetQuery): SpyDatasetQuery
    {
        $datasetQuery->leftJoinWithSpyDatasetLocalizedAttributes()
            ->useSpyDatasetRowColumnValueQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinSpyDatasetColumn()
                ->leftJoinSpyDatasetRow()
            ->endUse();

        return $datasetQuery;
    }
}
