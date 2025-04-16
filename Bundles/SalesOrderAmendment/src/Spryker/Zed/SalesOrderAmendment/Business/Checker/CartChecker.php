<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Checker;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\OrderReaderInterface;

class CartChecker implements CartCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CART_CANT_BE_AMENDED = 'sales_order_amendment.validation.cart.cart_cant_be_amended';

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Reader\OrderReaderInterface $orderReader
     */
    public function __construct(protected OrderReaderInterface $orderReader)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $quoteTransfer = $cartChangeTransfer->getQuoteOrFail();
        if (!$quoteTransfer->getAmendmentOrderReference()) {
            return (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        }

        $customerTransfer = $quoteTransfer->getCustomerOrFail();
        if ($quoteTransfer->getCustomerReference() === $customerTransfer->getCustomerReferenceOrFail()) {
            return (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        }

        $orderTransfer = $this->orderReader->findCustomerOrder(
            $quoteTransfer->getAmendmentOrderReferenceOrFail(),
            $customerTransfer->getCustomerReferenceOrFail(),
        );

        if ($orderTransfer) {
            return (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        }

        return (new CartPreCheckResponseTransfer())
            ->addMessage((new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_CANT_BE_AMENDED))
            ->setIsSuccess(false);
    }
}
