<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Persistence;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;

/**
 * Class DiscountQueryContainer
 */
class DiscountQueryContainer extends AbstractQueryContainer implements DiscountQueryContainerInterface
{

    /**
     * @param string $code
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery
     */
    public function queryVoucher($code)
    {
        return (new Propel\SpyDiscountVoucherQuery())->filterByCode($code);
    }

    /**
     * @param int $idDiscount
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery
     */
    public function queryDecisionRules($idDiscount)
    {
        return (new Propel\SpyDiscountDecisionRuleQuery())
            ->filterByFkDiscount($idDiscount);
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery
     */
    public function queryActiveAndRunningDiscounts()
    {
        $query = (new SpyDiscountQuery())
            ->filterByIsActive(true)
            ->where(
                '(' . SpyDiscountTableMap::COL_VALID_FROM . ' <= ? AND '
                . SpyDiscountTableMap::COL_VALID_TO . ' >= ? )',
                [
                    time(),
                    time(),
                ]
            )->_or()->where(
                SpyDiscountTableMap::COL_VALID_FROM . ' IS NULL AND '
                . SpyDiscountTableMap::COL_VALID_TO . ' IS NULL'
            );

        return $query;
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool()
    {
        return (new Propel\SpyDiscountVoucherPoolQuery());
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery
     */
    public function queryDiscount()
    {
        return (new Propel\SpyDiscountQuery());
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery
     */
    public function queryDiscountDecisionRule()
    {
        return (new Propel\SpyDiscountDecisionRuleQuery())
            ->joinDiscount()
            ->withColumn(SpyDiscountTableMap::COL_DISPLAY_NAME, 'discount_name')
            ->withColumn(SpyDiscountTableMap::COL_AMOUNT, 'discount_amount');
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher()
    {
        return (new Propel\SpyDiscountVoucherQuery())
            ->joinVoucherPool()
            ->withColumn(SpyDiscountVoucherPoolTableMap::COL_NAME, 'voucher_pool');
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool()
    {
        return (new Propel\SpyDiscountVoucherPoolQuery());
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPoolJoinedVoucherPoolCategory()
    {
        return $this->queryDiscountVoucherPool()
                    ->joinVoucherPoolCategory()
                    ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, 'category');
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategoryQuery
     */
    public function queryDiscountVoucherPoolCategory()
    {
        return (new Propel\SpyDiscountVoucherPoolCategoryQuery());
    }

}
