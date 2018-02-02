<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Orm\Zed\Dataset\Persistence\SpyDatasetColQuery;
use Orm\Zed\Dataset\Persistence\SpyDatasetQuery;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

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
    public function queryDashboard()
    {
        return SpyDatasetQuery::create();
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
        return $this->queryDashboard()->filterByIdDataset($idDataset);
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
        return $this->queryDashboardRow()->filterByTitle($title);
    }

    /**
     * @api
     *
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColQuery
     */
    public function queryDatasetColByTitle($title)
    {
        return $this->queryDashboardCol()->filterByTitle($title);
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowQuery
     */
    protected function queryDashboardRow()
    {
        return SpyDatasetRowQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColQuery
     */
    protected function queryDashboardCol()
    {
        return SpyDatasetColQuery::create();
    }
}
