<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

/**
 * Class DiscountQueryContainer
 */
interface DiscountQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucher($code);

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryActiveAndRunningDiscounts();

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool();

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscount();

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher();

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool();

    /**
     * @api
     *
     * @param string[] $voucherCodes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscountsBySpecifiedVouchers(array $voucherCodes = []);

    /**
     * @api
     *
     * @param array $codes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucherPoolByVoucherCodes(array $codes);

    /**
     * @api
     *
     * @param int $idVoucherCode
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherCodeByIdVoucherCode($idVoucherCode);

    /**
     * @api
     *
     * @param int $idVoucherPool
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVouchersByIdVoucherPool($idVoucherPool);

    /**
     * @api
     *
     * @param int $idVoucher
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucherByIdVoucher($idVoucher);

    /**
     * @api
     *
     * @param string $discountName
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscountName($discountName);

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    public function querySalesDiscount();

    /**
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryActiveCartRules();

    /**
     * @api
     *
     * @param int $idDiscountAmount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountAmountQuery
     */
    public function queryDiscountAmountById($idDiscountAmount);
}
