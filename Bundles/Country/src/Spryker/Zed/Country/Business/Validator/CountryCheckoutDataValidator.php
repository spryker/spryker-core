<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Country\Business\CountryManagerInterface;

class CountryCheckoutDataValidator implements CountryCheckoutDataValidatorInterface
{
    protected const COUNTRY_CODE_PARAMETER = '%code%';

    /**
     * @var \Spryker\Zed\Country\Business\CountryManagerInterface
     */
    protected $countryManager;

    /**
     * @param \Spryker\Zed\Country\Business\CountryManagerInterface $countryManager
     */
    public function __construct(CountryManagerInterface $countryManager)
    {
        $this->countryManager = $countryManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCountryCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = $this->validateCheckoutDataTransfer(
            $checkoutDataTransfer,
            (new CheckoutResponseTransfer())->setIsSuccess(true)
        );

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $checkoutResponseTransfer;
        }

        $billingAddressCountryIso2Code = $checkoutDataTransfer->getBillingAddress()->getIso2Code();
        if (!$this->countryManager->hasCountry($billingAddressCountryIso2Code)) {
            $this->addErrorToCheckoutResponseTransfer(
                $checkoutResponseTransfer,
                'billing.address.country.validation.not_found',
                [
                    static::COUNTRY_CODE_PARAMETER => $billingAddressCountryIso2Code,
                ]
            );
        }

        $shippingAddressCountryIso2Code = $checkoutDataTransfer->getShippingAddress()->getIso2Code();
        if (!$this->countryManager->hasCountry($shippingAddressCountryIso2Code)) {
            $this->addErrorToCheckoutResponseTransfer(
                $checkoutResponseTransfer,
                'shipping.address.country.validation.not_found',
                [
                    static::COUNTRY_CODE_PARAMETER => $shippingAddressCountryIso2Code,
                ]
            );
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function validateCheckoutDataTransfer(
        CheckoutDataTransfer $checkoutDataTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer {
        if (!$checkoutDataTransfer->getBillingAddress()) {
            $this->addErrorToCheckoutResponseTransfer($checkoutResponseTransfer, 'billing.address.validation.is_missing');
        }

        if (!$checkoutDataTransfer->getShippingAddress()) {
            $this->addErrorToCheckoutResponseTransfer($checkoutResponseTransfer, 'shipping.address.validation.is_missing');
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addErrorToCheckoutResponseTransfer(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        string $message,
        array $parameters = []
    ): CheckoutResponseTransfer {
        return $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError((new CheckoutErrorTransfer())
                ->setParameters($parameters)
                ->setMessage($message));
    }
}
