<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use Orm\Zed\Discount\Persistence\Map\SpyDiscountTableMap;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolCategoryTableMap;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolTableMap;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Orm\Zed\Sales\Persistence\SpySalesDiscountCodeQuery;
use Orm\Zed\Sales\Persistence\SpySalesDiscountQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Discount\Communication\Form\VoucherCodesForm;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

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
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucher($code)
    {
        return $this->getFactory()->createDiscountVoucherQuery()->filterByCode($code);
    }

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @param string[] $couponCodes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryCartRulesIncludingSpecifiedVouchers(array $couponCodes = [])
    {
        return $this->queryActiveAndRunningDiscounts()
            ->useVoucherPoolQuery()
                ->useDiscountVoucherQuery()
                    ->withColumn(SpyDiscountVoucherTableMap::COL_CODE, self::ALIAS_COL_USED_VOUCHER_CODE)
                    ->filterByCode(array_unique($couponCodes), Criteria::IN)
                ->endUse()
            ->endUse()
            ->_or()
            ->filterByFkDiscountVoucherPool(null)
            ->filterByIsActive(true);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool()
    {
        return $this->getFactory()->createDiscountVoucherPoolQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscount()
    {
        return $this->getFactory()->createDiscountQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountCollectorQuery
     */
    public function queryDiscountCollector()
    {
        return $this->getFactory()->createDiscountCollectorQuery();
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param int $idDiscountCollector
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountCollectorQuery
     */
    public function queryDiscountCollectorById($idDiscountCollector)
    {
        return $this->queryDiscountCollector()->filterByIdDiscountCollector($idDiscountCollector);
    }

    /**
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountCollectorQuery
     */
    public function queryDiscountCollectorByDiscountId($idDiscount)
    {
        return $this->queryDiscountCollector()->filterByFkDiscount($idDiscount);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher()
    {
        return $this->getFactory()->createDiscountVoucherQuery()
            ->joinVoucherPool()
            ->withColumn(SpyDiscountVoucherPoolTableMap::COL_NAME, 'voucher_pool');
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool()
    {
        return $this->getFactory()->createDiscountVoucherPoolQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPoolJoinedVoucherPoolCategory()
    {
        return $this->queryDiscountVoucherPool()
            ->joinVoucherPoolCategory()
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, 'category');
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategoryQuery
     */
    public function queryDiscountVoucherPoolCategory()
    {
        return $this->getFactory()->createDiscountVoucherPoolCategoryQuery();
    }

    /**
     * @api
     *
     * @param array $codes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucherPoolByVoucherCodes(array $codes)
    {
        return $this->queryDiscountVoucher()
            ->joinWithVoucherPool()
            ->filterByCode($codes, Criteria::IN);
    }

    /**
     * @api
     *
     * @param int $idVoucherCode
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
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
     * @api
     *
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $pool
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRuleQuery
     */
    public function queryDiscountCollectorBysByIdPool(SpyDiscountVoucherPool $pool)
    {
        return $this->queryDecisionRules($pool->getVirtualColumn(self::ALIAS_COL_ID_DISCOUNT));
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    public function querySalesDisount()
    {
        return new SpySalesDiscountQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscountCodeQuery
     */
    public function querySalesDisountCode()
    {
        return new SpySalesDiscountCodeQuery();
    }

}
