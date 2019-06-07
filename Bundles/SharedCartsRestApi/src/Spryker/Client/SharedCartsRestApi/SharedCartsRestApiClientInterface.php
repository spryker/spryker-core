<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareCartResponseTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

interface SharedCartsRestApiClientInterface
{
    /**
     * Specification:
     * - Finds quote's id by quote's UUID.
     * - Finds share details collection of quote by quote id.
     * - Makes Zed request.
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
     *  - Quote uuid should be provided in ShareCartRequestTransfer.
     *  - Company user id and quote permission group id should be provided in ShareDetailTransfer.
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
     *  - Quote company user uuid should be provided in ShareDetailTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function delete(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer;
}
