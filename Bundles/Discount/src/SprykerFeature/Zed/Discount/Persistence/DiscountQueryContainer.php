<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Persistence;

use SprykerFeature\Zed\Discount\Communication\Form\VoucherCodesType;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountCollectorQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery;
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
    const ALIAS_COL_USED_VOUCHER_CODE = 'UsedVoucherCode';

    /**
     * @param string $code
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery
     */
    public function queryVoucher($code)
    {
        return (new SpyDiscountVoucherQuery())->filterByCode($code);
    }

    /**
     * @param int $idDiscount
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery
     */
    public function queryDecisionRules($idDiscount)
    {
        return (new SpyDiscountDecisionRuleQuery())
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
                    $now->format('Y-m-d H:i:s'),
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
     * @return SpyDiscountQuery
     */
    public function queryCartRulesIncludingSpecifiedVouchers(array $couponCodes = [])
    {
        return $this->queryActiveAndRunningDiscounts()
            ->useVoucherPoolQuery()
                ->useDiscountVoucherQuery()
                    ->withColumn(SpyDiscountVoucherTableMap::COL_CODE, self::ALIAS_COL_USED_VOUCHER_CODE)
                    ->filterByCode(array_unique($couponCodes))
                ->endUse()
            ->endUse()
            ->_or()
            ->filterByFkDiscountVoucherPool(null)
            ->filterByIsActive(true);
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool()
    {
        return (new SpyDiscountVoucherPoolQuery());
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery
     */
    public function queryDiscount()
    {
        return (new SpyDiscountQuery());
    }

    /**
     * @return SpyDiscountCollectorQuery
     */
    public function queryDiscountCollector()
    {
        return new SpyDiscountCollectorQuery();
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery
     */
    public function queryDiscountDecisionRule()
    {
        return (new SpyDiscountDecisionRuleQuery())
            ->joinDiscount()
            ->withColumn(SpyDiscountTableMap::COL_DISPLAY_NAME, 'discount_name')
            ->withColumn(SpyDiscountTableMap::COL_AMOUNT, 'discount_amount');
    }

    /**
     * @param int $idDiscountCollector
     *
     * @return SpyDiscountCollectorQuery
     */
    public function queryDiscountCollectorById($idDiscountCollector)
    {
        return $this->queryDiscountCollector()->filterByIdDiscountCollector($idDiscountCollector);
    }

    /**
     * @param $idDiscount
     *
     * @return SpyDiscountCollectorQuery
     */
    public function queryDiscountCollectorByDiscountId($idDiscount)
    {
        return $this->queryDiscountCollector()->filterByFkDiscount($idDiscount);
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher()
    {
        return (new SpyDiscountVoucherQuery())
            ->joinVoucherPool()
            ->withColumn(SpyDiscountVoucherPoolTableMap::COL_NAME, 'voucher_pool');
    }

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool()
    {
        return (new SpyDiscountVoucherPoolQuery());
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
        return (new SpyDiscountVoucherPoolCategoryQuery());
    }

    /**
     * @param array $codes
     *
     * @return SpyDiscountVoucherQuery
     */
    public function queryVoucherPoolByVoucherCodes(array $codes)
    {
        return $this->queryDiscountVoucher()
            ->joinWithVoucherPool()
            ->filterByCode($codes);
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
            ->withColumn(SpyDiscountTableMap::COL_DESCRIPTION, self::ALIAS_COL_DESCRIPTION)
            ->filterByIdDiscountVoucherPool($idVoucherCode)
        ;
    }

    /**
     * @param int $idDiscountVoucherPool
     *
     * @return SpyDiscountCollectorQuery
     */
    public function queryDiscountCollectorBysByIdPool(SpyDiscountVoucherPool $pool)
    {
        return $this->queryDecisionRules($pool->getVirtualColumn(self::ALIAS_COL_ID_DISCOUNT));
    }

}
