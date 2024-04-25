<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Persistence;

use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;

interface MerchantCommissionEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommission(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountTransfer
     */
    public function createMerchantCommissionAmount(
        MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
    ): MerchantCommissionAmountTransfer;

    /**
     * @param int $idMerchantCommission
     * @param list<int> $storeIds
     *
     * @return void
     */
    public function createMerchantCommissionStores(int $idMerchantCommission, array $storeIds): void;

    /**
     * @param int $idMerchantCommission
     * @param list<int> $merchantIds
     *
     * @return void
     */
    public function createMerchantCommissionMerchants(int $idMerchantCommission, array $merchantIds): void;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function updateMerchantCommission(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountTransfer
     */
    public function updateMerchantCommissionAmount(
        MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
    ): MerchantCommissionAmountTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     *
     * @return void
     */
    public function deleteMerchantCommissionAmount(MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer): void;

    /**
     * @param int $idMerchantCommission
     * @param list<int> $storeIds
     *
     * @return void
     */
    public function deleteMerchantCommissionStores(int $idMerchantCommission, array $storeIds): void;

    /**
     * @param int $idMerchantCommission
     * @param list<int> $merchantIds
     *
     * @return void
     */
    public function deleteMerchantCommissionMerchants(int $idMerchantCommission, array $merchantIds): void;
}
