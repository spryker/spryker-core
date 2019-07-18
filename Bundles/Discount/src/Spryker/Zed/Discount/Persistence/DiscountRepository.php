<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Discount\Persistence\DiscountPersistenceFactory getFactory()
 */
class DiscountRepository extends AbstractRepository implements DiscountRepositoryInterface
{
    /**
     * @param string[] $codes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findVouchersExceedingUsageLimitByCodes(array $codes): ObjectCollection
    {
        return $this->getFactory()
            ->createDiscountVoucherQuery()
            ->filterByCode($codes, Criteria::IN)
            ->add(
                SpyDiscountVoucherTableMap::COL_MAX_NUMBER_OF_USES,
                SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES . '>=' . SpyDiscountVoucherTableMap::COL_MAX_NUMBER_OF_USES,
                Criteria::CUSTOM
            )
            ->find();
    }

    /**
     * @param string[] $codes
     *
     * @return int
     */
    public function getCountOfVouchersExceedingUsageLimitByCodes(array $codes): int
    {
        return $this->getFactory()
            ->createDiscountVoucherQuery()
            ->filterByCode($codes, Criteria::IN)
            ->filterByMaxNumberOfUses(0, Criteria::NOT_EQUAL)
            ->where(SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES . '>=' . SpyDiscountVoucherTableMap::COL_MAX_NUMBER_OF_USES)
            ->count();
    }
}
