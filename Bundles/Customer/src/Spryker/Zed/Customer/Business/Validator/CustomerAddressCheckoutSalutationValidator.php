<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Validator;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface;

class CustomerAddressCheckoutSalutationValidator implements CustomerAddressCheckoutSalutationValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_BILLING_ADDRESS_SALUTATION_INVALID = 'customer.billing_address.salutation.invalid';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_SHIPPING_ADDRESS_SALUTATION_INVALID = 'customer.shipping_address.salutation.invalid';

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @var \Spryker\Zed\Customer\CustomerConfig
     */
    protected CustomerConfig $customerConfig;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface $customerRepository
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerConfig $customerConfig
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerConfig = $customerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validate(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        /** @phpstan-var array<string> $availableSalutations */
        $availableSalutations = $this->customerRepository->getAllSalutations();

        if (
            !$this->validateBillingAddress(
                $quoteTransfer,
                $checkoutResponseTransfer,
                $availableSalutations,
            )
        ) {
            return false;
        }

        if (
            !$this->validateShippingAddress(
                $quoteTransfer,
                $checkoutResponseTransfer,
                $availableSalutations,
            )
        ) {
            return false;
        }

        return $this->validateItemsShippingAddresses(
            $quoteTransfer,
            $checkoutResponseTransfer,
            $availableSalutations,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param array<string> $availableSalutations
     *
     * @return bool
     */
    protected function validateBillingAddress(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        array $availableSalutations
    ): bool {
        if (
            !$quoteTransfer->getBillingAddress()
            || $this->validateAddress($quoteTransfer->getBillingAddressOrFail(), $availableSalutations)
        ) {
            return true;
        }

        $this->addErrorToCheckoutResponse(
            $checkoutResponseTransfer,
            static::GLOSSARY_KEY_CUSTOMER_BILLING_ADDRESS_SALUTATION_INVALID,
        );

        return false;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param array<string> $availableSalutations
     *
     * @return bool
     */
    protected function validateShippingAddress(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        array $availableSalutations
    ): bool {
        if (
            !$quoteTransfer->getShippingAddress()
            || $this->validateAddress($quoteTransfer->getShippingAddressOrFail(), $availableSalutations)
        ) {
            return true;
        }

        $this->addErrorToCheckoutResponse(
            $checkoutResponseTransfer,
            static::GLOSSARY_KEY_CUSTOMER_SHIPPING_ADDRESS_SALUTATION_INVALID,
        );

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param array<string> $availableSalutations
     *
     * @return bool
     */
    protected function validateItemsShippingAddresses(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        array $availableSalutations
    ): bool {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();
            if (
                !$shipmentTransfer
                || !$shipmentTransfer->getShippingAddress()
                || $this->validateAddress($shipmentTransfer->getShippingAddressOrFail(), $availableSalutations)
            ) {
                continue;
            }

            $this->addErrorToCheckoutResponse(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_CUSTOMER_SHIPPING_ADDRESS_SALUTATION_INVALID,
            );

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     *
     * @return void
     */
    protected function addErrorToCheckoutResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        string $message
    ): void {
        $checkoutResponseTransfer->setIsSuccess(false)
            ->addError(
                (new CheckoutErrorTransfer())
                    ->setErrorCode($this->customerConfig->getCustomerInvalidSalutationErrorCode())
                    ->setMessage($message),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param array<string> $availableSalutations
     *
     * @return bool
     */
    protected function validateAddress(AddressTransfer $addressTransfer, array $availableSalutations): bool
    {
        return !$addressTransfer->getSalutation()
            || in_array($addressTransfer->getSalutationOrFail(), $availableSalutations, true);
    }
}
