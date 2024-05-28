<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantExportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantPublisherConfigTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantFacadeInterface
{
    /**
     * Specification:
     * - Creates a new merchant entity.
     * - Requires the following data set on the MerchantTransfer:
     *   - name
     *   - registrationNumber
     *   - contactPersonTitle
     *   - contactPersonFirstName
     *   - contactPersonLastName
     *   - contactPersonPhone
     *   - email
     *   - storeRelation
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
     * - Calls a stack of `MerchantPostCreatePluginInterface` after merchant is created.
     * - Returns MerchantResponseTransfer.isSuccessful=false and error messages if merchant status transition is not valid.
     * - Returns MerchantResponseTransfer.isSuccessful=true and MerchantResponseTransfer.merchant.idMerchant is set from newly created entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Finds a merchant record by ID in DB.
     * - Requires the following data set on the MerchantTransfer:
     *   - idMerchant
     *   - name
     *   - registrationNumber
     *   - contactPersonTitle
     *   - contactPersonFirstName
     *   - contactPersonLastName
     *   - contactPersonPhone
     *   - email
     *   - storeRelation
     * - Calls a stack of `MerchantPostUpdatePluginInterface` after merchant is updated.
     * - Returns MerchantResponseTransfer.isSuccessful=false and error messages if merchant not found.
     * - Returns MerchantResponseTransfer.isSuccessful=false and error messages if merchant status transition is not valid.
     * - Persists the entity to DB.
     * - Returns MerchantResponseTransfer.isSuccessful=true and updated MerchantTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function updateMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Returns collection of merchants by provided criteria.
     * - Executes {@link \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface} plugin stack.
     * - Pagination, filter and ordering options can be passed to criteria.
     * - Pagination is controlled with page, maxPerPage, nbResults, previousPage, nextPage, firstIndex, lastIndex, firstPage and lastPage values.
     * - Filter supports ordering by field.
     * - Default order by merchant name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): MerchantCollectionTransfer;

    /**
     * Specification:
     * - Retrieves one merchant by provided criteria.
     * - Executes {@link \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface} plugin stack.
     * - Returns merchant which can filtered by merchant id and email.
     * - Returns MerchantTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findOne(MerchantCriteriaTransfer $merchantCriteriaTransfer): ?MerchantTransfer;

    /**
     * Specification:
     * - Gets the available merchant statuses for the current merchant status.
     * - Returns empty array if no available statuses exist.
     *
     * @api
     *
     * @param string $currentStatus
     *
     * @return array<string>
     */
    public function getApplicableMerchantStatuses(string $currentStatus): array;

    /**
     * Specification:
     * - Retrieves active Merchant entities from the Persistence.
     * - Filters `PriceProductMerchantRelationshipStorage` transfer objects by `Merchant.isActive` transfer property.
     * - Returns array of `PriceProductMerchantRelationshipStorage` transfers without ones that refer to deactivated merchants.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer> $priceProductMerchantRelationshipStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function filterPriceProductMerchantRelations(array $priceProductMerchantRelationshipStorageTransfers): array;

    /**
     * Specification:
     * - Retrieves Merchant entities from the Persistence.
     * - Filters Merchants by store if `MerchantExportCriteria.storeReference` is modified.
     * - Triggers Merchant.export event for Merchants filtered by the criteria.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer
     *
     * @return void
     */
    public function triggerMerchantExportEvents(MerchantExportCriteriaTransfer $merchantExportCriteriaTransfer): void;

    /**
     * Specification:
     * - Retrieves Merchant entities from the Persistence by the provided IDs.
     * - Executes {@link \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface} plugin stack.
     * - Requires MerchantPublisherConfigTransfer.merchantIds.
     * - Requires MerchantPublisherConfigTransfer.eventName.
     * - Sends MerchantPublisherConfigTransfer.eventName event to the event bus.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\MerchantPublisherConfigTransfer $merchantPublisherConfigTransfer
     *
     * @return void
     */
    public function emitPublishMerchantToMessageBroker(MerchantPublisherConfigTransfer $merchantPublisherConfigTransfer): void;
}
