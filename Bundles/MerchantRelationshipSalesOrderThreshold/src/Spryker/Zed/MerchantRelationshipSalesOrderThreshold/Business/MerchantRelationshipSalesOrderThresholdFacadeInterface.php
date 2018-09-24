<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface MerchantRelationshipSalesOrderThresholdFacadeInterface
{
    /**
     * Specification:
     * - Finds the applicable thresholds for a given SalesOrderThresholdQuoteTransfer.
     * - Based on quote the customer and the respective merchant relationships.
     * - Count the items with merchant relationship thresholds removeing them from SalesOrderThresholdQuoteTransfer::thresholdItems
     * - Also prepares the sales order threshold objects to be provided for the sales order threshold strategies.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    public function findApplicableThresholds(SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer): array;

    /**
     * Specification:
     * - Saves merchant relationship specific sales order threshold.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function saveMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer;

    /**
     * Specification:
     * - Deletes merchant relationship specific sales order threshold by MerchantRelationshipSalesOrderThresholdTransfer::idMerchantRelationshipSalesOrderThreshold.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function deleteMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): bool;

    /**
     * Specification:
     * - Gets array of MerchantRelationshipSalesOrderThresholdTransfer for merchant relationships, store and currency.
     * - Adds localized messages based on store locales for every merchant relationships
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int[] $merchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer[]
     */
    public function getMerchantRelationshipSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $merchantRelationshipIds
    ): array;
}
