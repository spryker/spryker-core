<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\CustomerCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Shared\Checkout\CheckoutConfig;
use SprykerFeature\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class PreconditionChecker implements PreconditionCheckerInterface
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
     * @param CheckoutRequestInterface $request
     * @param CheckoutResponseTransfer $response
     */
    public function checkPreconditions(CheckoutRequestInterface $request, CheckoutResponseTransfer $response)
    {
        if (!is_null($request->getIdUser())) {
            return;
        }

        if ($request->getIsGuest()) {
            return;
        }

        if ($this->customerFacade->hasEmail($request->getEmail())) {
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode(CheckoutConfig::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED)
                ->setMessage('Email already taken')
                ->setStep('email');

            $response
                ->setIsSuccess(false)
                ->addError($error)
            ;
        }
    }

}
