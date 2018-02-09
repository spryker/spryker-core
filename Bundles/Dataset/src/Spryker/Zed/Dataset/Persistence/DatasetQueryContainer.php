<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Orm\Zed\Dataset\Persistence\SpyDatasetQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Dataset\Persistence\DatasetPersistenceFactory getFactory()
 */
class DatasetQueryContainer extends AbstractQueryContainer implements DatasetQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDataset()
    {
        return $this->getFactory()->createDatasetQuery();
    }

    /**
     * @api
     *
     * @param int $idDataset
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetById($idDataset)
    {
        return $this->queryDataset()->filterByIdDataset($idDataset);
    }

    /**
     * @api
     *
     * @param string $datasetName
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetByName($datasetName)
    {
        return $this->queryDataset()->filterByName($datasetName);
    }

    /**
     * @api
     *
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowQuery
     */
    public function queryDatasetRowByTitle($title)
    {
        return $this->queryDatasetRow()->filterByTitle($title);
    }

    /**
     * @api
     *
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColumnQuery
     */
    public function queryDatasetColumnByTitle($title)
    {
        return $this->queryDatasetColumn()->filterByTitle($title);
    }

    /**
     * @api
     *
     * @param int $idDataset
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetByIdWithRelation($idDataset)
    {
        return $this->joinDatasetRelations(
            $this->queryDatasetById($idDataset)
        );
    }

    /**
     * @api
     *
     * @param string $datasetName
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetByNameWithRelation($datasetName)
    {
        return $this->joinDatasetRelations(
            $this->queryDatasetByName($datasetName)
        );
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowQuery
     */
    protected function queryDatasetRow()
    {
        return $this->getFactory()->createSpyDatasetRowQuery();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColumnQuery
     */
    protected function queryDatasetColumn()
    {
        return $this->getFactory()->createSpyDatasetColumnQuery();
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
