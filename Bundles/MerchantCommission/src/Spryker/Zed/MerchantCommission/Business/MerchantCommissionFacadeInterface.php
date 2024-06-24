<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business;

use Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;

interface MerchantCommissionFacadeInterface
{
    /**
     * Specification:
     * - Retrieves merchant commission entities filtered by criteria from Persistence.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.uuids` to filter by UUIDs.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.merchantCommissionIds` to filter by IDs.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.keys` to filter by keys.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.storeNames` to filter by store names.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.merchantIds` to filter by merchant IDs.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.merchantCommissionGroupNames` to filter by owner merchant commission group names.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.isActive` to filter by active status.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.withinValidityDates` to filter by validity dates relative to the current date.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.withStoreRelations` to load store relations.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.withMerchantRelations` to load merchant relations.
     * - Uses `MerchantCommissionCriteriaTransfer.merchantCommissionConditions.withCommissionMerchantAmountRelations` to load commission merchant amount relations.
     * - Uses `MerchantCommissionCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `MerchantCommissionCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `MerchantCommissionCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `MerchantCommissionCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `MerchantCommissionCollectionTransfer` filled with found merchant commissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function getMerchantCommissionCollection(
        MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
    ): MerchantCommissionCollectionTransfer;

    /**
     * Specification:
     * - Requires `MerchantCommissionCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `MerchantCommissionCollectionRequestTransfer.merchantCommissions` to be set.
     * - Requires `MerchantCommissionTransfer.key` to be set.
     * - Requires `MerchantCommissionTransfer.name` to be set.
     * - Requires `MerchantCommissionTransfer.calculatorTypePlugin` to be set.
     * - Requires `MerchantCommissionTransfer.isActive` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionGroup` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionGroup.uuid` to be set.
     * - Requires `MerchantCommissionTransfer.getStoreRelation` to be set.
     * - Expects `MerchantCommissionTransfer.getStoreRelation.store.name` to be set.
     * - Expects `MerchantCommissionTransfer.merchants.merchantReference` to be set.
     * - Expects `MerchantCommissionTransfer.merchantCommissionAmount.currency` to be set.
     * - Expects `MerchantCommissionTransfer.merchantCommissionAmount.currency.code` to be set.
     * - Validates that merchant commission keys don't exist in persistence.
     * - Validates that merchant commission keys are unique for every merchant commission within provided collection.
     * - Validates that merchant commission keys length is valid.
     * - Validates that merchant commission name length is valid.
     * - Validates that merchant commission description length is valid.
     * - Validates that merchant commission priority is in valid range.
     * - Validates that merchant commission validity dates are valid datetime.
     * - Validates that merchant commission validity period is valid.
     * - Validates that merchant commission group exists.
     * - Validates that related stores exists.
     * - Validates that related merchants exists.
     * - Validates that related currencies exists.
     * - Validates that plugin for calculator type exists.
     * - Validates that order condition is a correct query string.
     * - Validates that item condition is a correct query string.
     * - Uses `MerchantCommissionCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores merchant commissions at Persistence.
     * - Stores merchant commissions amounts at Persistence.
     * - Stores merchant commission store relations at Persistence.
     * - Stores merchant commission merchant relations at Persistence.
     * - Returns `MerchantCommissionCollectionResponseTransfer` with persisted merchant commissions and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function createMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `MerchantCommissionCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `MerchantCommissionCollectionRequestTransfer.merchantCommissions` to be set.
     * - Requires `MerchantCommissionTransfer.uuid` to be set.
     * - Requires `MerchantCommissionTransfer.key` to be set.
     * - Requires `MerchantCommissionTransfer.name` to be set.
     * - Requires `MerchantCommissionTransfer.calculatorTypePlugin` to be set.
     * - Requires `MerchantCommissionTransfer.isActive` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionGroup` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionGroup.uuid` to be set.
     * - Requires `MerchantCommissionTransfer.getStoreRelation` to be set.
     * - Expects `MerchantCommissionTransfer.getStoreRelation.store.name` to be set.
     * - Expects `MerchantCommissionTransfer.merchants.merchantReference` to be set.
     * - Expects `MerchantCommissionTransfer.merchantCommissionAmount.uuid` to be set.
     * - Expects `MerchantCommissionTransfer.merchantCommissionAmount.currency` to be set.
     * - Expects `MerchantCommissionTransfer.merchantCommissionAmount.currency.code` to be set.
     * - Validates that merchant commissions exist in persistence.
     * - Validates that merchant commission keys don't exist in persistence.
     * - Validates that merchant commission keys are unique for every merchant commission within provided collection.
     * - Validates that merchant commission keys length is valid.
     * - Validates that merchant commission name length is valid.
     * - Validates that merchant commission description length is valid.
     * - Validates that merchant commission priority is in valid range.
     * - Validates that merchant commission validity dates are valid datetime.
     * - Validates that merchant commission validity period is valid.
     * - Validates that merchant commission group exists.
     * - Validates that related stores exists.
     * - Validates that related merchants exists.
     * - Validates that related currencies exists.
     * - Validates that plugin for calculator type exists.
     * - Validates that order condition is a correct query string.
     * - Validates that item condition is a correct query string.
     * - Uses `MerchantCommissionCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores merchant commissions at Persistence.
     * - Stores merchant commissions amounts at Persistence.
     * - Stores merchant commission store relations at Persistence.
     * - Stores merchant commission merchant relations at Persistence.
     * - Returns `MerchantCommissionCollectionResponseTransfer` with persisted merchant commissions and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function updateMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `MerchantCommissionCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `MerchantCommissionCollectionRequestTransfer.merchantCommissions` to be set.
     * - Requires `MerchantCommissionTransfer.uuid` to be set.
     * - Requires `MerchantCommissionTransfer.key` to be set.
     * - Requires `MerchantCommissionTransfer.name` to be set.
     * - Requires `MerchantCommissionTransfer.calculationType` to be set.
     * - Requires `MerchantCommissionTransfer.isActive` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionGroup` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionGroup.key` to be set.
     * - Requires `MerchantCommissionTransfer.getStoreRelation` to be set.
     * - Requires `MerchantCommissionTransfer.getStoreRelation.store.name` to be set.
     * - Expects `MerchantCommissionTransfer.merchants.merchantReference` to be set.
     * - Expects `MerchantCommissionTransfer.merchantCommissionAmount.currency` to be set.
     * - Expects `MerchantCommissionTransfer.merchantCommissionAmount.currency.code` to be set.
     * - Determines what merchant commissions should be updated by provided merchant commissions keys.
     * - Validates that merchant commission keys are unique for every merchant commission within provided collection.
     * - Validates that merchant commission keys length is valid.
     * - Validates that merchant commission name length is valid.
     * - Validates that merchant commission description length is valid.
     * - Validates that merchant commission priority is in valid range.
     * - Validates that merchant commission validity dates are valid datetime.
     * - Validates that merchant commission validity period is valid.
     * - Validates that merchant commission group key exists.
     * - Validates that related stores exists.
     * - Validates that related merchants exists.
     * - Validates that related currencies exists.
     * - Uses `MerchantCommissionCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Creates merchant commissions with provided relations in Persistence if they do not already exist.
     * - Updates existing merchant commissions with all provided relation at Persistence.
     * - Returns `MerchantCommissionCollectionResponseTransfer` with persisted merchant commissions and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function importMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `MerchantCommissionCalculationRequestTransfer.idSalesOrder` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.store` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.store.name` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.idSalesOrderItem` to be set.
     * - Expects `MerchantCommissionCalculationRequestTransfer.items.merchantReference` to be set.
     * - Uses {@link \Spryker\Zed\MerchantCommission\MerchantCommissionConfig::getExcludedMerchantsFromCommission()} to exclude items with corresponding merchant references from commission calculation.
     * - Reads active merchant commissions from persistence.
     * - Filters out non-applicable commissions by merchant commission's order conditions.
     * - If merchant commission's order condition is empty, merchant commission counts as applicable.
     * - Collects commissionable items by merchant commission's item conditions.
     * - If merchant commission's item condition is empty, every item counts as commissionable.
     * - Groups applicable merchant commissions by merchant commission group.
     * - Calculates merchant commission amount for each commissionable item based on merchant commission priority.
     *   Only one merchant commission from merchant commission group is applied to the item.
     * - Uses {@link \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface} plugin to calculate commission amount.
     * - Adds applied commission to `MerchantCommissionCalculationItemTransfer`.
     * - Calculates total merchant commission amount for the order.
     * - Returns `MerchantCommissionCalculationResponseTransfer` with calculated merchant commissions for items and calculated merchant commission amount for the order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer
     */
    public function calculateMerchantCommission(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): MerchantCommissionCalculationResponseTransfer;

    /**
     * Specification:
     * - Requires `MerchantCommissionCalculationRequestTransfer.store` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.store.name` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.currency` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.currency.code` to be set.
     * - Requires `MerchantCommissionCalculationRequestItemTransfer.quantity` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionAmount.currency` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionAmount.currency.code` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionAmount.netAmount` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionAmount.grossAmount` to be set.
     * - Uses {@link \Spryker\Zed\MerchantCommission\MerchantCommissionConfig::getMerchantCommissionPriceModeForStore()} to get the price mode.
     * - Returns calculated merchant commission amount for configured price mode.
     * - Returns 0 if merchant commission amount for configured price mode is not provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return int
     */
    public function calculateFixedMerchantCommissionAmount(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): int;

    /**
     * Specification:
     * - Requires `MerchantCommissionCalculationRequestTransfer.store` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.store.name` to be set.
     * - Requires `MerchantCommissionCalculationRequestItemTransfer.sumNetPrice` to be set.
     * - Requires `MerchantCommissionCalculationRequestItemTransfer.sumGrossPrice` to be set.
     * - Requires `MerchantCommissionTransfer.amount` to be set.
     * - Calculates merchant commission amount for provided item.
     * - Rounds cent fraction for total merchant commission amount.
     * - Uses {@link \Spryker\Zed\MerchantCommission\MerchantCommissionConfig::getPercentageMerchantCommissionCalculationRoundMode()} to get the rounding config.
     * - Returns calculated merchant commission amount for configured price mode.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return int
     */
    public function calculatePercentageMerchantCommissionAmount(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): int;

    /**
     * Specification:
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.sku` to be set.
     * - Collects all items that match given the SKU in `RuleEngineClauseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collectByItemSku(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): array;

    /**
     * Specification:
     * - Check if the price mode in `RuleEngineClauseTransfer` equals the one provided in `MerchantCommissionCalculationRequestTransfer.priceMode`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function isPriceModeDecisionRuleSatisfiedBy(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): bool;

    /**
     * Specification:
     * - Requires `MerchantCommissionAmountTransformerRequestTransfer.calculatorPluginType` to be set.
     * - Requires `MerchantCommissionAmountTransformerRequestTransfer.amountForPersistence` to be set.
     * - Resolves {@link \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface} plugin for provided calculator type.
     * - Transforms provided amount to integer for persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return int
     */
    public function transformMerchantCommissionAmountForPersistence(
        MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
    ): int;

    /**
     * Specification:
     * - Requires `MerchantCommissionAmountTransformerRequestTransfer.calculatorPluginType` to be set.
     * - Requires `MerchantCommissionAmountTransformerRequestTransfer.amountForPersistence` to be set.
     * - Resolves {@link \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface} plugin for provided calculator type.
     * - Transforms provided persisted amount to float.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return float
     */
    public function transformMerchantCommissionAmountFromPersistence(
        MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
    ): float;

    /**
     * Specification:
     * - Requires `MerchantCommissionAmountFormatRequestTransfer.calculatorPluginType` to be set.
     * - Requires `MerchantCommissionAmountFormatRequestTransfer.amount` to be set.
     * - Expects `MerchantCommissionAmountFormatRequestTransfer.currency.code` to be set.
     * - Resolves {@link \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface} plugin for provided calculator type.
     * - Formats merchant commission amount to view format according to provided data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer $merchantCommissionAmountFormatRequestTransfer
     *
     * @return string
     */
    public function formatMerchantCommissionAmount(
        MerchantCommissionAmountFormatRequestTransfer $merchantCommissionAmountFormatRequestTransfer
    ): string;
}
