<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout\Address;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface AddressReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    public function getAddressesTransfer(QuoteTransfer $quoteTransfer): AddressesTransfer;
}
