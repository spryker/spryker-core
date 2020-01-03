<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface QuoteFacadeInterface
{
    /**
     * Specification:
     * - Verifies before saving if provided store is available, sets current store as default if not provided.
     * - Executes QuoteExpandBeforeCreatePluginInterface plugins.
     * - Applies QuoteValidatorPluginInterface validation plugins before saving.
     * - Reloads quote store by name if it's provided and doesn't have ID.
     * - Creates new quote entity if it does not exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Applies QuoteValidatorPluginInterface validation plugins before saving.
     * - Reloads quote store by name if it's provided and doesn't have ID.
     * - Updates existing quote entity from QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Finds quote for customer.
     *
     * @api
     *
     * @deprecated Use findQuoteByCustomerAndStore() instead.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomer(CustomerTransfer $customerTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Find quote for customer using a store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomerAndStore(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Finds quote by id.
     *
     * @api
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteById($idQuote): QuoteResponseTransfer;

    /**
     * Specification:
     * - Removes quote from DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Gets quote storage strategy type.
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy();

    /**
     * Specification:
     * - Gets quote collection filtered by criteria.
     * - Filters by FilterTransfer when provided.
     * - Filters by customer reference when provided.
     * - Filters by store ID when provided.
     * - Executes quote QuoteExpanderPluginInterface plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer;

    /**
     * Specification:
     * - Maps Quote Entity Transfer to quote transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer;

    /**
     * Specification:
     *  - Removes all expired guest quotes from database.
     *  - Guest quote lifetime is configured on application level.
     *
     * @api
     *
     * @return void
     */
    public function deleteExpiredGuestQuote(): void;

    /**
     * Specification:
     * - Finds quote by uuid.
     * - Requires uuid field to be set in QuoteTransfer.
     * - Uuid is not a required field and could be missing.
     *
     * @api
     *
     * {@internal will work if uuid field is provided.}
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     *  - Locks quote by setting `isLocked` transfer property to true.
     *  - Low level Quote locking (use CartFacadeInterface for features).
     *
     * @api
     *
     * @see CartFacadeInterface::resetQuoteLock()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     *  - Unlocks quote by setting `isLocked` transfer property to false.
     *  - Low level Quote unlocking (use CartFacadeInterface for features).
     *
     * @api
     *
     * @see CartFacadeInterface::resetQuoteLock()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function unlockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Returns true if provided quote is locked.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Validates quote.
     * - Returns error message when validation failed.
     * - Returns empty transfer if validation success.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validateQuote(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer;
}
