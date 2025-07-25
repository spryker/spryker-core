<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\Product\Persistence\Propel;

use Orm\Zed\Product\Persistence\Base\SpyProductQuery as BaseSpyProductQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_product' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyProductQuery extends BaseSpyProductQuery
{
    /**
     * @param string|null $relationAlias
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function leftJoinSpyProductBundleRelatedByFkBundledProduct(?string $relationAlias = null): SpyProductQuery
    {
        if (method_exists(BaseSpyProductQuery::class, 'leftJoinSpyProductBundleRelatedByFkBundledProduct')) {
            /** @phpstan-ignore-next-line */
            return parent::leftJoinSpyProductBundleRelatedByFkBundledProduct($relationAlias);
        }

        return $this->leftJoinBundledProduct($relationAlias);
    }
}
