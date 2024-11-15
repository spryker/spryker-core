<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Validator;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig;

class QuoteValidator implements QuoteValidatorInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig
     */
    protected SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig;

    /**
     * @var \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface
     */
    protected SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE = 'sales_order_amendment_oms.validation.order_not_amendable';

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig,
        SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
    ) {
        $this->salesOrderAmendmentOmsConfig = $salesOrderAmendmentOmsConfig;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validateQuote(
        CartReorderTransfer $cartReorderTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        if ($cartReorderTransfer->getQuoteOrFail()->getAmendmentOrderReference() === null) {
            return $cartReorderResponseTransfer;
        }

        if ($this->isOrderAmendable($cartReorderTransfer->getQuoteOrFail()->getAmendmentOrderReference())) {
            return $cartReorderResponseTransfer;
        }

        return $cartReorderResponseTransfer->addError(
            (new ErrorTransfer())->setMessage(static::GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE),
        );
    }

    /**
     * @param string $orderReference
     *
     * @return bool
     */
    protected function isOrderAmendable(string $orderReference): bool
    {
        $orderTransfer = (new OrderTransfer())->setOrderReference($orderReference);

        return $this->omsFacade->areOrderItemsSatisfiedByFlag(
            $orderTransfer,
            $this->salesOrderAmendmentOmsConfig->getAmendableOmsFlag(),
        );
    }
}
