<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use DateTime;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountTableMap;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolTableMap;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherTableMap;
use Orm\Zed\Sales\Persistence\SpySalesDiscountQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Formatter\OnDemandFormatter;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Discount\Persistence\DiscountPersistenceFactory getFactory()
 */
class DiscountQueryContainer extends AbstractQueryContainer implements DiscountQueryContainerInterface
{
    public const ALIAS_COL_ID_DISCOUNT = 'id_discount';
    public const ALIAS_COL_AMOUNT = 'amount';
    public const ALIAS_COL_TYPE = 'type';
    public const ALIAS_COL_DESCRIPTION = 'description';
    public const ALIAS_COL_VOUCHER_CODE = 'VoucherCode';
    public const ALIAS_VOUCHER_POOL_NAME = 'voucher_pool';

    /**
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucher($code)
    {
        return $this->getFactory()
            ->createDiscountVoucherQuery()
            ->filterByCode($code);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryActiveAndRunningDiscounts()
    {
        $now = new DateTime();
        $dateFormatted = $now->format('Y-m-d H:i:s');

        $query = $this->getFactory()
            ->createDiscountQuery()
            ->filterByIsActive(true)
            ->where(
                '(' . SpyDiscountTableMap::COL_VALID_FROM . ' <= ? AND ' . SpyDiscountTableMap::COL_VALID_TO . ' >= ? )',
                [
                    $dateFormatted,
                    $dateFormatted,
                ]
            )
            ->_or()
            ->where(
                '(' . SpyDiscountTableMap::COL_VALID_FROM . ' IS NULL AND ' . SpyDiscountTableMap::COL_VALID_TO . ' IS NULL )'
            );

        return $query;
    }

    /**
     * @api
     *
     * @param int $idStore
     * @param string[] $voucherCodes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscountsBySpecifiedVouchersForStore($idStore, array $voucherCodes = [])
    {
        $query = $this->queryActiveAndRunningDiscounts()
            ->setFormatter(OnDemandFormatter::class)
            ->useVoucherPoolQuery()
                ->useDiscountVoucherQuery()
                    ->withColumn(SpyDiscountVoucherTableMap::COL_CODE, self::ALIAS_COL_VOUCHER_CODE)
                    ->filterByCode(array_unique($voucherCodes), Criteria::IN)
                    ->orderByCode()
                ->endUse()
            ->endUse()
            ->useSpyDiscountStoreQuery()
                ->filterByFkStore($idStore)
            ->endUse();

        return $query;
    }

    /**
     * @api
     *
     * @param int $idStore
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryActiveCartRulesForStore($idStore)
    {
        $query = $this->queryActiveAndRunningDiscounts()
            ->filterByDiscountType(DiscountConstants::TYPE_CART_RULE)
            ->useSpyDiscountStoreQuery()
                ->filterByFkStore($idStore)
            ->endUse();

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool()
    {
        return $this->getFactory()
            ->createDiscountVoucherPoolQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscount()
    {
        return $this->getFactory()
            ->createDiscountQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher()
    {
        return $this->getFactory()
            ->createDiscountVoucherQuery()
            ->joinVoucherPool()
            ->withColumn(SpyDiscountVoucherPoolTableMap::COL_NAME, self::ALIAS_VOUCHER_POOL_NAME);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool()
    {
        return $this->getFactory()
            ->createDiscountVoucherPoolQuery();
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
            ->joinVoucherPool()
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
            ->withColumn(SpyDiscountTableMap::COL_ID_DISCOUNT, self::ALIAS_COL_ID_DISCOUNT)
            ->withColumn(SpyDiscountTableMap::COL_AMOUNT, self::ALIAS_COL_AMOUNT)
            ->withColumn(SpyDiscountTableMap::COL_DESCRIPTION, self::ALIAS_COL_DESCRIPTION)
            ->filterByIdDiscountVoucherPool($idVoucherCode);
    }

    /**
     * @api
     *
     * @param int $idVoucherPool
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVouchersByIdVoucherPool($idVoucherPool)
    {
        return $this->getFactory()
            ->createDiscountVoucherQuery()
            ->filterByFkDiscountVoucherPool($idVoucherPool);
    }

    /**
     * @api
     *
     * @param int $idVoucher
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucherByIdVoucher($idVoucher)
    {
        return $this->getFactory()
            ->createDiscountVoucherQuery()
            ->filterByIdDiscountVoucher($idVoucher);
    }

    /**
     * @api
     *
     * @param string $discountName
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscountName($discountName)
    {
        return $this->getFactory()
           ->createDiscountQuery()
           ->filterByDisplayName($discountName);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    public function querySalesDiscount()
    {
        return new SpySalesDiscountQuery();
    }

    /**
     * @api
     *
     * @param int $idDiscountAmount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountAmountQuery
     */
    public function queryDiscountAmountById($idDiscountAmount)
    {
        return $this->getFactory()
            ->createDiscountAmountQuery()
            ->filterByIdDiscountAmount($idDiscountAmount);
    }

    /**
     * @api
     *
     * @param int $idDiscount
     * @param int[] $idStores
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery
     */
    public function queryDiscountStoreByFkDiscountAndFkStores($idDiscount, array $idStores)
    {
        return $this->getFactory()
            ->createDiscountStoreQuery()
            ->filterByFkDiscount($idDiscount)
            ->filterByFkStore_In($idStores);
    }

    /**
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery
     */
    public function queryDiscountStoreWithStoresByFkDiscount($idDiscount)
    {
        return $this->getFactory()
            ->createDiscountStoreQuery()
            ->leftJoinWithSpyStore()
            ->filterByFkDiscount($idDiscount);
    }

    /**
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscountWithStoresByFkDiscount($idDiscount)
    {
        return $this->getFactory()
            ->createDiscountQuery()
            ->filterByIdDiscount($idDiscount)
            ->leftJoinWithSpyDiscountStore()
            ->useSpyDiscountStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyStore()
            ->endUse();
    }
}
