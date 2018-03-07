<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;

interface QuoteRepositoryInterface
{
    /**
     * Specification:
     * - Find quote by customer reference
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function findQuoteByCustomer($customerReference): ?QuoteTransfer;

    /**
     * Specification:
     * - Find quote by quote id
     *
     * @api
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\SpyQuoteEntityTransfer|null
     */
    public function findQuoteById($idQuote): ?SpyQuoteEntityTransfer;
}
