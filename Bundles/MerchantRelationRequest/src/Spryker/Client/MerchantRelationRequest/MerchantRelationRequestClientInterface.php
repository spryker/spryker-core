<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationRequest;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;

interface MerchantRelationRequestClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves merchant relation request entities filtered by criteria from Persistence.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.uuids` to filter by UUIDs.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.merchantRelationRequestIds` to filter by IDs.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.statuses` to filter by statuses.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.companyIds` to filter by company IDs.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.merchantIds` to filter by merchant IDs.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.companyUserIds` to filter by company user IDs.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.ownerCompanyBusinessUnitIds` to filter by owner company business unit IDs.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.rangeCreatedAt.from` to specify the starting date for filtering by the creation date of the entity.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.rangeCreatedAt.to` to specify the ending date for filtering by the creation date of the entity.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestConditions.withAssigneeCompanyBusinessUnitRelations` to load assignee company business unit relations.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestSearchConditions.ownerCompanyBusinessUnitName` to search by owner company business unit name.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestSearchConditions.ownerCompanyBusinessUnitCompanyName` to search by owner company business unit company name.
     * - Uses `MerchantRelationRequestCriteriaTransfer.merchantRelationRequestSearchConditions.assigneeCompanyBusinessUnitName` to search by assignee company business unit names.
     * - Uses `MerchantRelationRequestCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `MerchantRelationRequestCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `MerchantRelationRequestCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `MerchantRelationRequestCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Executes stack of {@link \Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface} plugins.
     * - Returns `MerchantRelationRequestCollectionTransfer` filled with found merchant relation requests.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function getMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCollectionTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires `MerchantRelationRequestCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `MerchantRelationRequestCollectionRequestTransfer.merchantRelationRequests` to be set.
     * - Requires `MerchantRelationRequestTransfer.status` to be set.
     * - Requires `MerchantRelationRequestTransfer.companyUser.idCompanyUser` to be set.
     * - Requires `MerchantRelationRequestTransfer.merchant.idMerchant` to be set.
     * - Requires `MerchantRelationRequestTransfer.ownerCompanyBusinessUnit.idCompanyBusinessUnit` to be set.
     * - Requires `MerchantRelationRequestTransfer.ownerCompanyBusinessUnit.fkCompany` to be set.
     * - Requires at least one `MerchantRelationRequestTransfer.assigneeCompanyBusinessUnits` to be set.
     * - Validates that merchant relation requests statuses are "pending".
     * - Validates that decision notes are empty.
     * - Validates that request notes are not exceeding length limit.
     * - Validates that assignee company business units are not.
     * - Validates that assignee company business units are unique within each merchant relation request.
     * - Validates that merchants are active.
     * - Validates that both company business unit and company user are exist for provided company.
     * - Uses `MerchantRelationRequestCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores merchant relation requests at Persistence.
     * - Stores assignee company business units at Persistence.
     * - Executes stack of {@link \Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostCreatePluginInterface} plugins.
     * - Returns `MerchantRelationRequestCollectionResponseTransfer` with persisted merchant relation requests and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function createMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires `MerchantRelationRequestCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `MerchantRelationRequestCollectionRequestTransfer.merchantRelationRequests` to be set.
     * - Requires `MerchantRelationRequestTransfer.uuid` to be set.
     * - Requires `MerchantRelationRequestTransfer.status` to be set.
     * - Requires at least one `MerchantRelationRequestTransfer.assigneeCompanyBusinessUnits` to be set.
     * - Validates merchant relation request existence using `MerchantRelationRequestTransfer.uuid`.
     * - Validates that decision notes are not exceeding length limit.
     * - Validates that provided merchant relation request status meets required criteria.
     * - Uses `MerchantRelationRequestCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores updated merchant relation requests at Persistence.
     * - Stores updated assignee company business units at Persistence.
     * - If status is changed to "approved", creates merchant relation for the merchant and company business unit.
     * - If `MerchantRelationRequestTransfer.isSplitEnabled` is set to true, creates separate merchant relation for each assignee company business unit.
     * - Executes stack of {@link \Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostUpdatePluginInterface} plugins.
     * - Returns `MerchantRelationRequestCollectionResponseTransfer` with persisted merchant relation requests and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function updateMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer;
}
