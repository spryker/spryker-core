<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\CustomerConfig;

class PreConditionChecker implements PreConditionCheckerInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\Customer\Customer
     */
    protected $customer;

    /**
     * @param \Spryker\Zed\Customer\Business\Customer\Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkPreConditions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($quoteTransfer->getCustomer() !== null && $quoteTransfer->getCustomer()->getIdCustomer() !== null) {
            return true;
        }

        if ($quoteTransfer->getCustomer()->getIsGuest() === true) {
            return true;
        }

        if ($this->customer->hasEmail($quoteTransfer->getCustomer()->getEmail())) {
            $checkoutErrorTransfer = $this->createCheckoutErrorTransfer();
            $checkoutErrorTransfer
                ->setErrorCode(CustomerConfig::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED)
                ->setMessage('Email already taken');

            $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($checkoutErrorTransfer);

            return false;
        }

        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return new CheckoutErrorTransfer();
    }
}
