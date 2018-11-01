<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart;

use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

interface SharedCartClientInterface
{
    /**
     * Specification:
     * - Get quote permission group list.
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
     * - Adds share information to quote.
     * - Updates quote in database.
     *
     * @api
     *
     * @deprecated Please use SharedCartClientInterface::updateQuotePermissions() instead
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addShareCart(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Remove share information from quote.
     * - Updates quote in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeShareCart(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Calculate current customer permission on given quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function getQuoteAccessLevel(QuoteTransfer $quoteTransfer): ?string;

    /**
     * Specification:
     * - Updates quote with permissions from share details.
     * - Sends Zed request to update quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuotePermissions(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Returns share detail collection by quote id.
     *  - Retrieves ShareDetails collection from Zed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailsByIdQuoteAction(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer;

    /**
     * Specification:
     * - Сhecks the possibility of removing the quote.
     * - If customer isn't owner of quote but has permission for write this quote - return TRUE
     * - If customer has another quote where he is owner - return TRUE
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteDeletable(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     *  - Sends Zed Request to get share detail collection by quote id.
     *  - Filters quote share detail from share details by company user id.
     *  - Sends Zed request to update quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function dismissSharedCart(ShareCartRequestTransfer $shareCartRequestTransfer): QuoteResponseTransfer;
}
