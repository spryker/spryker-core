<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout\Zed;

use Generated\Shared\Transfer\CheckoutRequestTransfer;

interface CheckoutStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $transferCheckout
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $transferCheckout);

}
