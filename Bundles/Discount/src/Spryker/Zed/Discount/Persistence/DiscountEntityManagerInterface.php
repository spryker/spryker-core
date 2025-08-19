<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use Generated\Shared\Transfer\DiscountAmountCriteriaTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

interface DiscountEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function createDiscount(DiscountTransfer $discountTransfer): DiscountTransfer;

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function updateDiscount(DiscountTransfer $discountTransfer): DiscountTransfer;

    /**
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer $discountMoneyAmountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountMoneyAmountTransfer
     */
    public function createDiscountAmount(DiscountMoneyAmountTransfer $discountMoneyAmountTransfer): DiscountMoneyAmountTransfer;

    /**
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer $discountMoneyAmountTransfer
     *
     * @return void
     */
    public function updateDiscountAmount(DiscountMoneyAmountTransfer $discountMoneyAmountTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\DiscountGeneralTransfer $discountGeneralTransfer
     *
     * @return int
     */
    public function createDiscountVoucherPool(DiscountGeneralTransfer $discountGeneralTransfer): int;

    /**
     * @param int $idDiscount
     * @param array<int> $storeIds
     *
     * @return void
     */
    public function createDiscountStoreRelations(int $idDiscount, array $storeIds): void;

    /**
     * @param \Generated\Shared\Transfer\DiscountGeneralTransfer $discountGeneralTransfer
     *
     * @return int
     */
    public function updateDiscountVoucherPool(DiscountGeneralTransfer $discountGeneralTransfer): int;

    /**
     * @param \Generated\Shared\Transfer\DiscountAmountCriteriaTransfer $discountAmountCriteriaTransfer
     *
     * @return void
     */
    public function deleteDiscountAmounts(DiscountAmountCriteriaTransfer $discountAmountCriteriaTransfer): void;

    /**
     * @param int $idDiscount
     * @param array<int> $storeIds
     *
     * @return void
     */
    public function deleteDiscountStoreRelations(int $idDiscount, array $storeIds): void;

    /**
     * @param list<int> $salesDiscountIds
     *
     * @return void
     */
    public function deleteSalesDiscountsBySalesDiscountIds(array $salesDiscountIds): void;

    /**
     * @param list<int> $salesDiscountIds
     *
     * @return void
     */
    public function deleteSalesDiscountCodesBySalesDiscountIds(array $salesDiscountIds): void;

    /**
     * @param int $idDiscountVoucherPool
     *
     * @return void
     */
    public function deleteDiscountVouchersByIdDiscountVoucherPool(int $idDiscountVoucherPool): void;

    /**
     * @param int $idDiscountVoucherPool
     *
     * @return void
     */
    public function deleteDiscountVoucherPoolByIdDiscountVoucherPool(int $idDiscountVoucherPool): void;
}
