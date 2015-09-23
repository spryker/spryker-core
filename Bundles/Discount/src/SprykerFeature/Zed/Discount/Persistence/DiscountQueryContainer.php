<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Persistence;

use SprykerFeature\Zed\Discount\Communication\Form\VoucherCodesType;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;

/**
 * Class DiscountQueryContainer
 */
class DiscountQueryContainer extends AbstractQueryContainer implements DiscountQueryContainerInterface
{
    const ALIAS_COL_ID_DISCOUNT = 'id_discount';
    const ALIAS_COL_AMOUNT = 'amount';
    const ALIAS_COL_TYPE = 'type';
    const ALIAS_COL_DESCRIPTION = 'description';

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
        $now = new \DateTime();

        $query = (new SpyDiscountQuery())
            ->filterByIsActive(true)
            ->where(
                '(' . SpyDiscountTableMap::COL_VALID_FROM . ' <= ? AND '
                . SpyDiscountTableMap::COL_VALID_TO . ' >= ? )',
                [
                    $now->format('Y-m-d H:i:s'),
                    $now->format('Y-m-d H:i:s')
                ]
            )->_or()->where(
                SpyDiscountTableMap::COL_VALID_FROM . ' IS NULL AND '
                . SpyDiscountTableMap::COL_VALID_TO . ' IS NULL'
            );

        return $query;
    }

    /**
     * @param array|string[] $couponCodes
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery
     */
    public function queryCartRulesIncludingSpecifiedVouchers(array $couponCodes = [])
    {
        return $this->queryActiveAndRunningDiscounts()
            ->useVoucherPoolQuery()
                ->useDiscountVoucherQuery()
                    ->filterByCode(array_unique($couponCodes))
                ->endUse()
            ->endUse()
            ->_or()
            ->filterByFkDiscountVoucherPool(null);
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

    /**
     * @param int $idVoucherCode
     *
     * @return SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherCodeByIdVoucherCode($idVoucherCode)
    {
        return $this->queryDiscountVoucherPool()
            ->useDiscountQuery()
                ->useDecisionRuleQuery()
                ->endUse()
            ->endUse()
            ->joinVoucherPoolCategory(SpyDiscountVoucherPoolCategoryTableMap::TABLE_NAME)
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, VoucherCodesType::VOUCHER_POOL_CATEGORY)
            ->withColumn(SpyDiscountTableMap::COL_ID_DISCOUNT, self::ALIAS_COL_ID_DISCOUNT)
            ->withColumn(SpyDiscountTableMap::COL_AMOUNT, self::ALIAS_COL_AMOUNT)
            ->withColumn(SpyDiscountTableMap::COL_TYPE, self::ALIAS_COL_TYPE)
            ->withColumn(SpyDiscountTableMap::COL_DESCRIPTION, self::ALIAS_COL_DESCRIPTION)
            ->filterByIdDiscountVoucherPool($idVoucherCode)
        ;
    }

    /**
     * @param SpyDiscountVoucherPool $pool
     *
     * @return SpyDiscountDecisionRuleQuery
     */
    public function queryDiscountDecisionRulesByIdPool(SpyDiscountVoucherPool $pool)
    {
        return $this->queryDecisionRules($pool->getVirtualColumn(DiscountQueryContainer::ALIAS_COL_ID_DISCOUNT));
    }


}
