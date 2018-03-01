<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function findQuoteByCustomer($customerReference);
}
