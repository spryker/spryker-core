<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Shared\CustomerCheckoutConnector\CustomerCheckoutConnectorConstants;
use Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class PreConditionChecker implements PreConditionCheckerInterface
{

    /**
     * @var \Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface
     */
    private $customerFacade;

    /**
     * @param \Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface $customerFacade
     */
    public function __construct(CustomerCheckoutConnectorToCustomerInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $response
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
                ->setErrorCode(CustomerCheckoutConnectorConstants::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED)
                ->setMessage('Email already taken')
                ->setStep('email');

            $response
                ->setIsSuccess(false)
                ->addError($error);
        }
    }

}
