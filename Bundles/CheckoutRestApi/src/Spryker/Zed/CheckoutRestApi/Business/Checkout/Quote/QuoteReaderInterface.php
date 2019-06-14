<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

interface QuoteReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findCustomerQuoteByUuid(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer;
}
