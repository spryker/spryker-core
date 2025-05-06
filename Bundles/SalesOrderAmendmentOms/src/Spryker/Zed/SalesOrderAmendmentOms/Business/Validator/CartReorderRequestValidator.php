<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Validator;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

class CartReorderRequestValidator implements CartReorderRequestValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE = 'sales_order_amendment_oms.validation.order_not_amendable';

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\OrderValidatorInterface $orderValidator
     */
    public function __construct(protected OrderValidatorInterface $orderValidator)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validate(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        if (!$cartReorderRequestTransfer->getIsAmendment()) {
            return $cartReorderResponseTransfer;
        }

        if ($this->orderValidator->validateIsOrderAmendable($cartReorderRequestTransfer->getOrderReferenceOrFail())) {
            return $cartReorderResponseTransfer;
        }

        return $cartReorderResponseTransfer->addError(
            (new ErrorTransfer())->setMessage(static::GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE),
        );
    }
}
