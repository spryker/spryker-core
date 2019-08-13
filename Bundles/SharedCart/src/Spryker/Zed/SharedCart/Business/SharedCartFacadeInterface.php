<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareCartResponseTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer;

interface SharedCartFacadeInterface
{
    /**
     * Specification:
     * - Get permissions for customer company user.
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser($idCompanyUser): PermissionCollectionTransfer;

    /**
     * Specification:
     * - Adds customer shared cart filtered by store to QuoteResponseTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expandQuoteResponseWithSharedCarts(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Add base shared quote permission group list to database.
     *
     * @api
     *
     * @return void
     */
    public function installSharedCartPermissions(): void;

    /**
     * Specification:
     * - Get filtered quote permission groups list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer
     */
    public function getQuotePermissionGroupList(QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer): QuotePermissionGroupResponseTransfer;

    /**
     * Specification:
     * - Update quote share details for quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateQuoteShareDetails(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Reset is_default flag for all quotes shared with customer.
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function resetQuoteDefaultFlagByCustomer(int $idCompanyUser): void;

    /**
     * Specification:
     * - Mark share connection for quote and customer as default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function quoteSetDefault(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Remove all share connection for quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function deleteShareForQuote(QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     *  - Add quote permissions for customer company user to customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomer(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * Specification:
     *  - Checks if shared cart default for company user.
     *
     * @api
     *
     * @param int $idQuote
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function isSharedQuoteDefault(int $idQuote, int $idCompanyUser): bool;

    /**
     * Specification:
     *  - Returns share details collection of quote by quote id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailsByIdQuote(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer;

    /**
     * Specification:
     *  - Un-shares quotes for company user.
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function deleteShareRelationsForCompanyUserId(int $idCompanyUser): void;

    /**
     * Specification:
     *  - Shares cart to company user with permission group.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return void
     */
    public function addQuoteCompanyUser(ShareCartRequestTransfer $shareCartRequestTransfer): void;

    /**
     * Specification:
     *  - Finds quote permission group by id.
     *  - Requires idQuotePermissionGroup field to be set in QuotePermissionGroupTransfer.
     *  - If quote permission group not found, returns QuotePermissionGroupResponseTransfer with `isSuccess=false`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer
     */
    public function findQuotePermissionGroupById(QuotePermissionGroupTransfer $quotePermissionGroupTransfer): QuotePermissionGroupResponseTransfer;

    /**
     * Specification:
     * - Expands a collection of quotes with collection of quotes shared with the customer.
     * - Expands each quote with the QuotePermissionGroup the user has assigned to him.
     * - Requires CompanyUser::idCompanyUser to be set on the CustomerTransfer taken as a parameter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function expandQuoteCollectionWithCustomerSharedQuoteCollection(
        QuoteCollectionTransfer $quoteCollectionTransfer,
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer;

    /**
     * Specification:
     *  - Returns the share details collection.
     *  - Collection can be filtered by the provided filter transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailCollectionByShareDetailCriteria(ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer): ShareDetailCollectionTransfer;

    /**
     * Specification:
     *  - Finds quote company user by uuid.
     *  - Requires uuid field to be set in QuoteCompanyUserTransfer taken as parameter.
     *
     * @api
     *
     * {@internal will work if UUID field is provided.}
     *
     * @param \Generated\Shared\Transfer\QuoteCompanyUserTransfer $quoteCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCompanyUserTransfer|null
     */
    public function findQuoteCompanyUserByUuid(QuoteCompanyUserTransfer $quoteCompanyUserTransfer): ?QuoteCompanyUserTransfer;

    /**
     * Specification:
     * - Shares cart to company user with permission group.
     * - Requires ShareDetailTransfer to be set in ShareCartRequestTransfer.
     * - Requires idQuote, idCompanyUser and QuotePermissionGroupTransfer to be set on ShareDetailTransfer.
     * - Returns sharing details.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function createQuoteCompanyUser(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer;

    /**
     * Specification:
     * - Updates permission group for shared cart.
     * - Requires ShareDetailTransfer to be set in ShareCartRequestTransfer.
     * - Requires idQuoteCompanyUser and QuotePermissionGroupTransfer to be set in ShareDetailTransfer.
     * - Returns sharing details.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function updateQuoteCompanyUserPermissionGroup(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer;

    /**
     * Specification:
     * - Removes sharing of the quote.
     * - Requires ShareDetailTransfer to be set in ShareCartRequestTransfer.
     * - Requires idQuoteCompanyUser to be set in ShareDetailTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return void
     */
    public function deleteQuoteCompanyUser(ShareCartRequestTransfer $shareCartRequestTransfer): void;

    /**
     * Specification:
     * - Creates cart share for provided Quote and provided company user within the same business unit.
     * - Updates permission to Full-access, if resource was shared with higher permission.
     * - Returns 'isSuccessful=true' with ResourceShareTransfer if cart was shared successfully.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function shareCartByResourceShareRequest(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer;

    /**
     * Specification:
     *  - Finds all customers (including quote owner) that have access to the quote.
     *  - QuoteTransfer.idQuote is required
     *  - QuoteTransfer.customerReference is required
     *  - QuoteTransfer.customerTransfer.customerReference is required
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByQuote(QuoteTransfer $quoteTransfer): CustomerCollectionTransfer;
}
