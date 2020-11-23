<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface;

class CustomerAddressValidator implements CustomerAddressValidatorInterface
{
    protected const GLOSSARY_PARAMETER_ID = '%id%';
    protected const GLOSSARY_KEY_CUSTOMER_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND = 'checkout.validation.customer_address.not_found';
    protected const GLOSSARY_KEY_CUSTOMER_ADDRESSES_APPLICABLE_FOR_CUSTOMERS_ONLY = 'Customer addresses are applicable only for customers.';

    /**
     * @var \Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(CustomersRestApiToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCustomerAddressesInCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $shippingAddressUuids = $this->extractShippingAddressUuids($checkoutDataTransfer);

        if (!$shippingAddressUuids) {
            return $checkoutResponseTransfer;
        }

        if (!$this->isLoggedCustomer($checkoutDataTransfer)) {
            return $this->getErrorResponse(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_CUSTOMER_ADDRESSES_APPLICABLE_FOR_CUSTOMERS_ONLY
            );
        }

        $customerShippingAddressUuids = $this->getCustomerShippingAddressUuids($checkoutDataTransfer);

        foreach ($shippingAddressUuids as $shippingAddressUuid) {
            if (!in_array($shippingAddressUuid, $customerShippingAddressUuids, true)) {
                $checkoutResponseTransfer = $this->getErrorResponse(
                    $checkoutResponseTransfer,
                    static::GLOSSARY_KEY_CUSTOMER_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND,
                    [static::GLOSSARY_PARAMETER_ID => $shippingAddressUuid]
                );
            }
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return bool
     */
    protected function isLoggedCustomer(CheckoutDataTransfer $checkoutDataTransfer): bool
    {
        return $checkoutDataTransfer->getQuote()
            && $checkoutDataTransfer->getQuote()->getCustomer()
            && $checkoutDataTransfer->getQuote()->getCustomer()->getIdCustomer();
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return string[]
     */
    protected function getCustomerShippingAddressUuids(CheckoutDataTransfer $checkoutDataTransfer): array
    {
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($checkoutDataTransfer->getQuote()->getCustomer()->getIdCustomer());

        $customerShippingAddressUuids = [];
        $addressesTransfer = $this->customerFacade->getAddresses($customerTransfer);

        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $customerShippingAddressUuids[] = $addressTransfer->getUuid();
        }

        return $customerShippingAddressUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return string[]
     */
    protected function extractShippingAddressUuids(CheckoutDataTransfer $checkoutDataTransfer): array
    {
        $shippingAddressUuids = [];

        foreach ($checkoutDataTransfer->getShipments() as $restShipmentsTransfer) {
            $restAddressTransfer = $restShipmentsTransfer->getShippingAddress();

            if ($restAddressTransfer && $restAddressTransfer->getId()) {
                $shippingAddressUuids[] = $restAddressTransfer->getId();
            }
        }

        return $shippingAddressUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     * @param string[] $parameters
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function getErrorResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        string $message,
        array $parameters = []
    ): CheckoutResponseTransfer {
        return $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError(
                (new CheckoutErrorTransfer())
                    ->setMessage($message)
                    ->setParameters($parameters)
            );
    }
}
