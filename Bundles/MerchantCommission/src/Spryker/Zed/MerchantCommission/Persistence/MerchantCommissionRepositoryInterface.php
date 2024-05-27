<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Persistence;

use Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupCriteriaTransfer;

interface MerchantCommissionRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function getMerchantCommissionCollection(
        MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
    ): MerchantCommissionCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountCriteriaTransfer $merchantCommissionAmountCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer
     */
    public function getMerchantCommissionAmountCollection(
        MerchantCommissionAmountCriteriaTransfer $merchantCommissionAmountCriteriaTransfer
    ): MerchantCommissionAmountCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionGroupCriteriaTransfer $merchantCommissionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer
     */
    public function getMerchantCommissionGroupCollection(
        MerchantCommissionGroupCriteriaTransfer $merchantCommissionGroupCriteriaTransfer
    ): MerchantCommissionGroupCollectionTransfer;

    /**
     * @param int $idMerchantCommission
     *
     * @return list<int>
     */
    public function getStoreIdsRelatedToMerchantCommission(int $idMerchantCommission): array;

    /**
     * @param int $idMerchantCommission
     *
     * @return list<int>
     */
    public function getMerchantIdsRelatedToMerchantCommission(int $idMerchantCommission): array;

    /**
     * @param list<string> $merchantCommissionKeys
     *
     * @return list<string>
     */
    public function getExistingMerchantCommissionKeys(array $merchantCommissionKeys): array;
}
