<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;

interface MerchantSwitcherFacadeInterface
{
    /**
     * Specification:
     * - Goes through items and checks if ItemTransfer.merchantReference equals to QuoteTransfer.merchantReference.
     * - If values are not equal the method forbids to proceed with checkout and add error message.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkMerchantReference(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;
}
