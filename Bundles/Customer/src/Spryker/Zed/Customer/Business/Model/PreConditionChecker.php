<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Customer\Business\Customer\CustomerInterface;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface;

class PreConditionChecker implements PreConditionCheckerInterface
{
    public const ERROR_EMAIL_INVALID = 'customer.email.invalid';
    public const ERROR_EMAIL_UNIQUE = 'customer.email.already.used';

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\CustomerInterface
     */
    protected $customer;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @param \Spryker\Zed\Customer\Business\Customer\CustomerInterface $customer
     * @param \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface $utilValidateService
     */
    public function __construct(CustomerInterface $customer, CustomerToUtilValidateServiceInterface $utilValidateService)
    {
        $this->customer = $customer;
        $this->utilValidateService = $utilValidateService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkPreConditions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($this->hasIdCustomer($quoteTransfer)) {
            return true;
        }

        $result = $this->checkValidEmail($quoteTransfer, $checkoutResponseTransfer);

        if ($quoteTransfer->getCustomer()->getIsGuest() === true) {
            return $result;
        }

        $result &= $this->checkExistingEmail($quoteTransfer, $checkoutResponseTransfer);

        return (bool)$result;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function checkValidEmail(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if (!$this->utilValidateService->isEmailFormatValid($quoteTransfer->getCustomer()->getEmail())) {
            $this->addViolation(
                $checkoutResponseTransfer,
                CustomerConfig::ERROR_CODE_CUSTOMER_INVALID_EMAIL,
                static::ERROR_EMAIL_INVALID
            );

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function checkExistingEmail(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($this->customer->hasEmail($quoteTransfer->getCustomer()->getEmail())) {
            $this->addViolation(
                $checkoutResponseTransfer,
                CustomerConfig::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED,
                static::ERROR_EMAIL_UNIQUE
            );

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasIdCustomer(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getCustomer() !== null && $quoteTransfer->getCustomer()->getIdCustomer() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param int $errorCode
     * @param string $errorMessage
     *
     * @return void
     */
    protected function addViolation(CheckoutResponseTransfer $checkoutResponseTransfer, $errorCode, $errorMessage)
    {
        $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError(
                $this->createCheckoutErrorTransfer()
                    ->setErrorCode($errorCode)
                    ->setMessage($errorMessage)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return new CheckoutErrorTransfer();
    }
}
