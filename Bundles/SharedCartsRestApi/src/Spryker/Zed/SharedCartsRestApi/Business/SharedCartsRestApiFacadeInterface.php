<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareCartResponseTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

interface SharedCartsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Finds quote's id by quote's UUID.
     * - Finds share details collection of quote by quote id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer;

    /**
     * Specification:
     *  - Shares a quote with company user.
     *  - Quote uuid and ShareDetailTransfer should be provided in ShareCartRequestTransfer.
     *  - Company user id and quote permission group should be provided in ShareDetailTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function create(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer;

    /**
     * Specification:
     *  - Updates permission group for shared cart.
     *  - ShareDetailTransfer should be provided in ShareCartRequestTransfer.
     *  - Quote company user uuid and quote permission group id should be provided in ShareDetailTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function update(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer;

    /**
     * Specification:
     *  - Removes sharing of the quote.
     *  - ShareDetailTransfer should be provided in ShareCartRequestTransfer.
     *  - Quote company user uuid should be provided in ShareDetailTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function delete(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer;

    /**
     * Specification:
     * - Expands QuoteTransfer with QuotePermissionGroupTransfer if applicable.
     * - Will expand only if QuoteTransfer::$customer is a company user the cart is shared with.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithQuotePermissionGroup(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
