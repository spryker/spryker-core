<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface FactFinderQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param int $IdLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getExportDataQuery($IdLocale);

    /**
     * @api
     *
     * @param int $IdLocale
     * @param int $rootCategoryNodeId
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getParentCategoryQuery($IdLocale, $rootCategoryNodeId);

}
