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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucher($code);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryActiveAndRunningDiscounts();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscount();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idStore
     * @param string[] $voucherCodes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscountsBySpecifiedVouchersForStore($idStore, array $voucherCodes = []);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $codes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucherPoolByVoucherCodes(array $codes);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idVoucherCode
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherCodeByIdVoucherCode($idVoucherCode);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idVoucherPool
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVouchersByIdVoucherPool($idVoucherPool);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idVoucher
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function queryVoucherByIdVoucher($idVoucher);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $discountName
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscountName($discountName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    public function querySalesDiscount();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idStore
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryActiveCartRulesForStore($idStore);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idDiscountAmount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountAmountQuery
     */
    public function queryDiscountAmountById($idDiscountAmount);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idDiscount
     * @param int[] $idStores
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery
     */
    public function queryDiscountStoreByFkDiscountAndFkStores($idDiscount, array $idStores);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery
     */
    public function queryDiscountStoreWithStoresByFkDiscount($idDiscount);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function queryDiscountWithStoresByFkDiscount($idDiscount);
}
