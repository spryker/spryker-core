<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

interface SharedCartsRestApiClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
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
}
