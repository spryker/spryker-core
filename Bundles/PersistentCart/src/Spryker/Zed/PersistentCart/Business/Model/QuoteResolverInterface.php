<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;

interface QuoteResolverInterface
{
    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer|null $quoteUpdateRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resolveCustomerQuote(
        int $idQuote,
        CustomerTransfer $customerTransfer,
        QuoteUpdateRequestAttributesTransfer $quoteUpdateRequestAttributesTransfer = null
    ): QuoteResponseTransfer;
}
