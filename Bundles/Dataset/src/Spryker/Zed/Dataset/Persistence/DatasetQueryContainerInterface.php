<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface DatasetQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDataset();

    /**
     * @api
     *
     * @param int $idDataset
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetById($idDataset);

    /**
     * @api
     *
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetRowByTitle($title);

    /**
     * @api
     *
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetColumnByTitle($title);

    /**
     * @api
     *
     * @param int $idDataset
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetByIdWithRelation($idDataset);

    /**
     * @api
     *
     * @param string $nameDataset
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetByNameWithRelation($nameDataset);

    /**
     * @api
     *
     * @param string $nameDataset
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetByName($nameDataset);
}
