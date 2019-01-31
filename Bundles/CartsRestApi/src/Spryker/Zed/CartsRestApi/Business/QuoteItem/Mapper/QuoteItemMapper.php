<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;

class QuoteItemMapper implements QuoteItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapRestCartItemRequestTransferToQuoteTransfer(
        RestCartItemRequestTransfer $restCartItemRequestTransfer
    ): QuoteTransfer {
        return (new QuoteTransfer())
            ->setUuid($restCartItemRequestTransfer->getCartUuid())
            ->setCustomerReference($restCartItemRequestTransfer->getCustomerReference());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function createPersistentCartChangeTransfer(
        QuoteTransfer $quoteTransfer,
        RestCartItemRequestTransfer $restCartItemRequestTransfer
    ): PersistentCartChangeTransfer {
        return (new PersistentCartChangeTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->addItem($restCartItemRequestTransfer->getCartItem())
            ->setCustomer((new CustomerTransfer())->setCustomerReference($restCartItemRequestTransfer->getCustomerReference()));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer
     */
    public function createPersistentCartChangeQuantityTransfer(
        QuoteTransfer $quoteTransfer,
        RestCartItemRequestTransfer $restCartItemRequestTransfer
    ): PersistentCartChangeQuantityTransfer {
        return (new PersistentCartChangeQuantityTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setItem($restCartItemRequestTransfer->getCartItem())
            ->setCustomer((new CustomerTransfer())->setCustomerReference($restCartItemRequestTransfer->getCustomerReference()));
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestTransfer
     */
    public function createRestQuoteRequestTransfer(
        RestCartItemRequestTransfer $restCartItemRequestTransfer
    ): RestQuoteRequestTransfer {
        return (new RestQuoteRequestTransfer())
            ->setQuote((new QuoteTransfer()))
            ->setCustomerReference($restCartItemRequestTransfer->getCustomerReference());
    }
}
