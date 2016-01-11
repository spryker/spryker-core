<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Checkout\CheckoutConstants;
use Spryker\Zed\Customer\Business\Customer\Customer;

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
     * @return void
     */
    public function checkPreConditions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($quoteTransfer->getCustomer() !== null && $quoteTransfer->getCustomer()->getIdCustomer() !== null) {
            return;
        }

        if ($quoteTransfer->getCustomer()->getIsGuest() === true) {
            return;
        }

        if ($this->customer->hasEmail($quoteTransfer->getCustomer()->getEmail())) {
            $checkoutErrorTransfer = $this->createCheckoutErrorTransfer();
            $checkoutErrorTransfer
                ->setErrorCode(CheckoutConstants::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED)
                ->setMessage('Email already taken')
                ->setStep('email');

            $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($checkoutErrorTransfer);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return new CheckoutErrorTransfer();
    }

}
