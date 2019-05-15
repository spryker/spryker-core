<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;

interface QuoteCollectionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function expandQuoteCollectionWithCustomerSharedQuoteCollection(
        CustomerTransfer $customerTransfer,
        QuoteCollectionTransfer $quoteCollectionTransfer
    ): QuoteCollectionTransfer;
}
