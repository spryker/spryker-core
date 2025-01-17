<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Validator;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

class CartReorderValidator implements CartReorderValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_REFERENCE_NOT_MATCH = 'sales_order_amendment.validation.cart_reorder.order_reference_not_match';

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validate(
        CartReorderTransfer $cartReorderTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        if ($this->isOrderReferenceMatch($cartReorderTransfer)) {
            return $cartReorderResponseTransfer;
        }

        return $cartReorderResponseTransfer->addError(
            (new ErrorTransfer())->setMessage(static::GLOSSARY_KEY_ORDER_REFERENCE_NOT_MATCH),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return bool
     */
    protected function isOrderReferenceMatch(CartReorderTransfer $cartReorderTransfer): bool
    {
        return $cartReorderTransfer->getQuoteOrFail()->getAmendmentOrderReferenceOrFail() === $cartReorderTransfer->getOrderOrFail()->getOrderReferenceOrFail();
    }
}
