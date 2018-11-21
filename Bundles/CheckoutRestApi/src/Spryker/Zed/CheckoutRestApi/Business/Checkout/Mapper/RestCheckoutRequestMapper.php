<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

class RestCheckoutRequestMapper implements RestCheckoutRequestMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapAddresses(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $restQuoteRequestTransfer = $restCheckoutRequestAttributesTransfer->getCart();
        if ($restQuoteRequestTransfer->getBillingAddress() !== null) {
            $billingAddress = (new AddressTransfer())
                ->fromArray($restQuoteRequestTransfer->getBillingAddress()->toArray(), true)
                ->setUuid($restQuoteRequestTransfer->getBillingAddress()->getId());
            $quoteTransfer->setBillingAddress($billingAddress);
        }

        if ($restQuoteRequestTransfer->getShippingAddress() !== null) {
            $shippingAddress = (new AddressTransfer())
                ->fromArray($restQuoteRequestTransfer->getShippingAddress()->toArray(), true)
                ->setUuid($restQuoteRequestTransfer->getShippingAddress()->getId());
            $quoteTransfer->setShippingAddress($shippingAddress);
        }

        return $quoteTransfer;
    }
}
