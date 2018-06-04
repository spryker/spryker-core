<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

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
     * @param int $idDataset
     *
     * @return bool
     */
    public function hasDatasetData($idDataset)
    {
        $count = $this->getFactory()->createSpyDatasetRowColumnValueQuery()->filterByFkDataset($idDataset)->count();

        return ($count) ? true : false;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasDatasetName($name)
    {
        return !empty($this->getFactory()->createDatasetQuery()->filterByName($name)->count());
    }

    /**
     * @param int $idDataset
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetByIdWithRelation($idDataset)
    {
        $datasetEntity = $this->joinDatasetRelations(
            $this->getFactory()->createDatasetQuery()->filterByIdDataset($idDataset)
        )->find()->getFirst();

        if (!$datasetEntity) {
            throw new DatasetNotFoundException();
        }

        return $this->getFactory()->createDatasetMapper()->getResponseDatasetTransfer($datasetEntity);
    }

    /**
     * @param string $datasetName
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetByNameWithRelation($datasetName)
    {
        $datasetEntity = $this->joinDatasetRelations(
            $this->getFactory()->createDatasetQuery()->filterByName($datasetName)
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
    protected function joinDatasetRelations(SpyDatasetQuery $datasetQuery)
    {
        return $datasetQuery->leftJoinWithSpyDatasetLocalizedAttributes()
            ->useSpyDatasetRowColumnValueQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinSpyDatasetColumn()
                ->leftJoinSpyDatasetRow()
            ->endUse();
    }
}
