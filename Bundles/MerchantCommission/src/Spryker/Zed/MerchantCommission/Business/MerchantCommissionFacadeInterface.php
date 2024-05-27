<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business;

use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;

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
}
