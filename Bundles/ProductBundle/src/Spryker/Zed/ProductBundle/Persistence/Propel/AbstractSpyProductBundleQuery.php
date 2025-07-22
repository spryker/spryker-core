<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\ProductBundle\Persistence\Propel;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundleQuery as BaseSpyProductBundleQuery;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_product_bundle' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyProductBundleQuery extends BaseSpyProductBundleQuery
{
    /**
     *  BC method; PropelRM ObjectBuilder is updated and uses phpName for generating method names. We added phpName="BundledProduct"
     *  to the foreignKey definition and by that this method no longer exists. To prevent projects form breaking up when using this
     *  (no longer generated) method this one is added.
     *
     * @deprecated Can be removed with the next major.
     *
     * @see useBundledProductQuery()
     *
     * @param string|null $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery A secondary query class using the current class as primary query
     */
    public function useSpyProductRelatedByFkBundledProductQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN): SpyProductQuery
    {
        if (method_exists(BaseSpyProductBundleQuery::class, 'useSpyProductRelatedByFkBundledProductQuery')) {
            /** @phpstan-ignore-next-line */
            return parent::useSpyProductRelatedByFkBundledProductQuery($relationAlias, $joinType);
        }

        return $this->useBundledProductQuery($relationAlias, $joinType);
    }

    /**
     * @deprecated Can be removed with the next major.
     *
     * @param string $joinType
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery
     */
    public function joinWithSpyProductRelatedByFkBundledProduct(string $joinType = Criteria::INNER_JOIN): SpyProductBundleQuery
    {
        if (method_exists(BaseSpyProductBundleQuery::class, 'joinWithSpyProductRelatedByFkBundledProduct')) {
            /** @phpstan-ignore-next-line */
            return parent::joinWithSpyProductRelatedByFkBundledProduct($joinType);
        }

        return $this->joinWithBundledProduct($joinType);
    }
}
