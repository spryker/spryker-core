<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Shared\Checkout\CheckoutConstants;
use SprykerFeature\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class PreConditionChecker implements PreConditionCheckerInterface
{

    /**
     * @var CustomerCheckoutConnectorToCustomerInterface
     */
    private $customerFacade;

    /**
     * @param CustomerCheckoutConnectorToCustomerInterface $customerFacade
     */
    public function __construct(CustomerCheckoutConnectorToCustomerInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param CheckoutRequestTransfer $request
     * @param CheckoutResponseTransfer $response
     *
     * @return void
     */
    public function checkPreConditions(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response)
    {
        if ($request->getIdUser() !== null) {
            return;
        }

        if ($request->getIsGuest()) {
            return;
        }

        if ($this->customerFacade->hasEmail($request->getEmail())) {
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
