<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface SalesOrderThresholdFacadeInterface
{
    /**
     * Specification:
     * - Populates the database with sales order threshold strategies of `SalesOrderThresholdConfig`.
     *
     * @api
     *
     * @return void
     */
    public function installSalesOrderThresholdTypes(): void;

    /**
     * Specification:
     * - Sets sales order threshold.
     * - If the threshold type wasn't configured, it will throw and exception.
     * - Generates a glossary key for the threshold message if it wasn't provided.
     * - Saves the message translations too.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function saveSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer;

    /**
     * Specification:
     * - Deletes sales order threshold by SalesOrderThresholdTransfer::idSalesOrderThreshold.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return bool
     */
    public function deleteSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): bool;

    /**
     * Specification:
     * - Get sales order threshold strategy for a given key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function getSalesOrderThresholdTypeByKey(
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
    ): SalesOrderThresholdTypeTransfer;

    /**
     * Specification:
     * - Gets Global Thresholds by Store and Currency.
     * - Adds localized messages based on store locales for every merchant relationships
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer[]
     */
    public function getSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array;

    /**
     * Specification:
     * - Checks quote value/values against sales order hard thresholds.
     * - Also adds the messages to CheckoutResponseTransfer, if any
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCheckoutSalesOrderThreshold(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool;

    /**
     * Specification:
     * - Retrieves sales order threshold expenses from the quote and saves it to the database.
     * - These plugins are already enveloped into a transaction.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function saveSalesOrderSalesOrderThresholdExpense(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer;

    /**
     * Specification:
     * - Validate if values of fee and threshold is valid for the given strategy.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return bool
     */
    public function isThresholdValid(
        SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
    ): bool;

    /**
     * Specification:
     * - Adds info messages when soft threshold is not met.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSalesOrderThresholdMessages(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Removes sales order threshold expenses from CalculableObjectTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeSalesOrderThresholdExpenses(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * Specification:
     * - Gets SalesOrderThresholdTransfers from data sources.
     * - Resolves SalesOrderThresholdTransfers to StrategyPlugin.
     * - Adds expenses from SalesOrderThresholdTransfer to CalculableObjectTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function addSalesOrderThresholdExpenses(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * Specification:
     * - Gets SalesOrderThreshold tax set id from database.
     *
     * @api
     *
     * @return int|null
     */
    public function findSalesOrderThresholdTaxSetId(): ?int;

    /**
     * Specification:
     * - Saves SalesOrderThreshold tax set id to database.
     *
     * @api
     *
     * @param int $idTaxSet
     *
     * @return void
     */
    public function saveSalesOrderThresholdTaxSet(int $idTaxSet): void;

    /**
     * Specification:
     * - Resolves $typeKey to SalesOrderThresholdTypeTransfer from threshold types
     * - Saves and returns SalesOrderThresholdType
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function saveSalesOrderThresholdType(SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer): SalesOrderThresholdTypeTransfer;
}
