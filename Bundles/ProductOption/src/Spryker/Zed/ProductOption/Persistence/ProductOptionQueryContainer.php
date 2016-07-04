<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionPersistenceFactory getFactory()
 */
class ProductOptionQueryContainer extends AbstractQueryContainer implements ProductOptionQueryContainerInterface
{
    /**
     * @param int $idProductOptionGroup
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupByIdProductOptionGroup($idProductOptionGroup)
    {
        return $this->getFactory()
            ->createProductOptionGroupQuery()
            ->filterByIdProductOptionGroup($idProductOptionGroup);
    }


    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku)
    {
        return $this->getFactory()
            ->createProductAbstractQuery()
            ->filterBySku($sku);
    }

}
