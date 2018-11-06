<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacadeInterface getFacade()
 */
class CheckoutRestApiAddressPlugin extends AbstractPlugin implements CheckoutPreSaveHookInterface
{
    /**
     * {@inheritdoc}
     * - Checks if UUID is provided in the addresses transfer.
     * - Populates idCustomerAddress found by that UUID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preSave(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): QuoteTransfer
    {
        return $this->getFacade()->expandQuoteAddressesWithCustomerAddressById($quoteTransfer, $checkoutResponseTransfer);
    }
}
