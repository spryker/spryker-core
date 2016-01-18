<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesDiscountCodeQuery;
use Orm\Zed\Sales\Persistence\SpySalesDiscountQuery;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Discount\Communication\Form\VoucherCodesForm;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolTableMap;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountTableMap;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolCategoryTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;

/**
 * @method \Spryker\Zed\Discount\Persistence\DiscountPersistenceFactory getFactory()
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucher($code)
    {
        return $this->getFactory()->createDiscountVoucherQuery()->filterByCode($code);
    }

    /**
     * @param int $idDiscount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRuleQuery
     */
    public function queryDecisionRules($idDiscount)
    {
        return $this->getFactory()->createDiscountDecisionRuleQuery()
            ->filterByFkDiscount($idDiscount);
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryActiveAndRunningDiscounts()
    {
        $now = new \DateTime();

        $query = $this->getFactory()->createDiscountQuery()
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool()
    {
        return $this->getFactory()->createDiscountVoucherPoolQuery();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscount()
    {
        return $this->getFactory()->createDiscountQuery();
    }

    /**
     * @return SpyDiscountCollectorQuery
     */
    public function queryDiscountCollector()
    {
        return $this->getFactory()->createDiscountCollectorQuery();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRuleQuery
     */
    public function queryDiscountDecisionRule()
    {
        return $this->getFactory()->createDiscountDecisionRuleQuery()
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
     * @param int $idDiscount
     *
     * @return SpyDiscountCollectorQuery
     */
    public function queryDiscountCollectorByDiscountId($idDiscount)
    {
        return $this->queryDiscountCollector()->filterByFkDiscount($idDiscount);
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher()
    {
        return $this->getFactory()->createDiscountVoucherQuery()
            ->joinVoucherPool()
            ->withColumn(SpyDiscountVoucherPoolTableMap::COL_NAME, 'voucher_pool');
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool()
    {
        return $this->getFactory()->createDiscountVoucherPoolQuery();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPoolJoinedVoucherPoolCategory()
    {
        return $this->queryDiscountVoucherPool()
            ->joinVoucherPoolCategory()
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, 'category');
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategoryQuery
     */
    public function queryDiscountVoucherPoolCategory()
    {
        return $this->getFactory()->createDiscountVoucherPoolCategoryQuery();
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
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, VoucherCodesForm::FIELD_VOUCHER_POOL_CATEGORY)
            ->withColumn(SpyDiscountTableMap::COL_ID_DISCOUNT, self::ALIAS_COL_ID_DISCOUNT)
            ->withColumn(SpyDiscountTableMap::COL_AMOUNT, self::ALIAS_COL_AMOUNT)
            ->withColumn(SpyDiscountTableMap::COL_DESCRIPTION, self::ALIAS_COL_DESCRIPTION)
            ->filterByIdDiscountVoucherPool($idVoucherCode);
    }

    /**
     * @param SpyDiscountVoucherPool $pool
     *
     * @throws PropelException
     *
     * @return SpyDiscountDecisionRuleQuery
     */
    public function queryDiscountCollectorBysByIdPool(SpyDiscountVoucherPool $pool)
    {
        return $this->queryDecisionRules($pool->getVirtualColumn(self::ALIAS_COL_ID_DISCOUNT));
    }

    /**
     * @return SpySalesDiscountQuery
     */
    public function querySalesDisount()
    {
        return new SpySalesDiscountQuery();
    }

    /**
     * @return SpySalesDiscountCodeQuery
     */
    public function querySalesDisountCode()
    {
        return new SpySalesDiscountCodeQuery();
    }

}
