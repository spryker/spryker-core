<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Checkout\CheckoutConstants;
use Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class PreConditionChecker implements PreConditionCheckerInterface
{

    /**
     * @var CustomerCheckoutConnectorToCustomerInterface
     */
    protected $customerFacade;

    /**
     * @param CustomerCheckoutConnectorToCustomerInterface $customerFacade
     */
    public function __construct(CustomerCheckoutConnectorToCustomerInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $response
     *
     * @return void
     */
    public function checkPreConditions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $response)
    {
        if ($quoteTransfer->getCustomer() !== null && $quoteTransfer->getCustomer()->getIdCustomer() !== null) {
            return;
        }

        if ($quoteTransfer->getCustomer()->getIsGuest() === true) {
            return;
        }

        if ($this->customerFacade->hasEmail($quoteTransfer->getCustomer()->getEmail())) {
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode(CheckoutConstants::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED)
                ->setMessage('Email already taken')
                ->setStep('email');

            $response
                ->setIsSuccess(false)
                ->addError($error);
        }
    }

}
