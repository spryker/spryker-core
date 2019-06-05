<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;

class QuoteItemMapper implements QuoteItemMapperInterface
{
    /**
     * @deprecated Use mapCartItemsRequestTransferToQuoteTransfer() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapRestCartItemsAttributesTransferToQuoteTransfer(
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        return $quoteTransfer
            ->setUuid($restCartItemsAttributesTransfer->getQuoteUuid())
            ->setCustomerReference($restCartItemsAttributesTransfer->getCustomerReference())
            ->setCustomer($restCartItemsAttributesTransfer->getCustomer());
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCartItemsRequestTransferToQuoteTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        return $quoteTransfer
            ->setUuid($cartItemRequestTransfer->getQuoteUuid())
            ->setCustomerReference($cartItemRequestTransfer->getCustomer()->getCustomerReference())
            ->setCustomer($cartItemRequestTransfer->getCustomer());
    }
}
