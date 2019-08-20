<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Discount\Persistence\DiscountPersistenceFactory getFactory()
 */
class DiscountRepository extends AbstractRepository implements DiscountRepositoryInterface
{
    /**
     * @param string[] $codes
     *
     * @return string[]
     */
    public function findVoucherCodesExceedingUsageLimit(array $codes): array
    {
        return $this->getFactory()
            ->createDiscountVoucherQuery()
            ->filterByCode($codes, Criteria::IN)
            ->filterByMaxNumberOfUses(0, Criteria::GREATER_THAN)
            ->where(SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES . '>=' . SpyDiscountVoucherTableMap::COL_MAX_NUMBER_OF_USES)
            ->select(SpyDiscountVoucherTableMap::COL_CODE)
            ->find()
            ->toArray();
    }
}
