<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface MerchantRelationshipSalesOrderThresholdRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param array<int> $merchantRelationshipIds
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer>
     */
    public function getMerchantRelationshipSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $merchantRelationshipIds
    ): array;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer|null
     */
    public function findMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): ?MerchantRelationshipSalesOrderThresholdTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCriteriaTransfer $merchantRelationshipSalesOrderThresholdCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionTransfer
     */
    public function getMerchantRelationshipSalesOrderThresholdCollection(
        MerchantRelationshipSalesOrderThresholdCriteriaTransfer $merchantRelationshipSalesOrderThresholdCriteriaTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionTransfer;
}
