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
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDashboard();

    /**
     * @param int $idDataset
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetById($idDataset);

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetRowByTitle($title);

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function queryDatasetColByTitle($title);
}
