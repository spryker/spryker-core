<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Persistence;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchPersistenceFactory getFactory()
 */
class ProductLabelSearchQueryContainer extends AbstractQueryContainer implements ProductLabelSearchQueryContainerInterface
{
    const FK_PRODUCT_ABSTRACT = 'fkProductAbstract';

    /**
     * @api
     *
     * @param array $productLabelIds
     *
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelByProductLabelIds(array $productLabelIds)
    {
        return $this->getFactory()
            ->createSpyProductLabelProductAbstractQuery()
            ->filterByFkProductLabel_In($productLabelIds)
            ->withColumn(SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT, static::FK_PRODUCT_ABSTRACT)
            ->select([static::FK_PRODUCT_ABSTRACT]);
    }
}
