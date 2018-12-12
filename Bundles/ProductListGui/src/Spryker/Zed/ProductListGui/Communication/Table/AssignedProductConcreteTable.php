<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Orm\Zed\Product\Persistence\SpyProductQuery;

class AssignedProductConcreteTable extends AbstractProductConcreteTable
{
    protected const DEFAULT_URL = 'assignedProductConcreteTable';
    protected const TABLE_IDENTIFIER = self::DEFAULT_URL;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $spyProductQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function filterQuery(SpyProductQuery $spyProductQuery): SpyProductQuery
    {
        $spyProductQuery
            ->useSpyProductListProductConcreteQuery()
                ->filterByFkProductList($this->getIdProductList())
            ->endUse();

        return $spyProductQuery;
    }
}
