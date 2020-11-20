<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Validator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomerAddressValidator implements CustomerAddressValidatorInterface
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     */
    public function __construct(CustomersRestApiToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateCustomerAddresses(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        $shippingAddressUuids = $this->extractShippingAddressUuids($restCheckoutRequestAttributesTransfer);

        if (!$shippingAddressUuids) {
            return $restErrorCollectionTransfer;
        }

        if (!$this->isLoggedCustomer($restCheckoutRequestAttributesTransfer)) {
            return $this->buildErrorMessage(
                CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_ADDRESSES_APPLICABLE_FOR_CUSTOMERS_ONLY,
                CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ADDRESSES_APPLICABLE_FOR_CUSTOMERS_ONLY
            );
        }

        $customerShippingAddressUuids = $this->getCustomerShippingAddressUuids($restCheckoutRequestAttributesTransfer);

        foreach ($shippingAddressUuids as $shippingAddressUuid) {
            if (!in_array($shippingAddressUuid, $customerShippingAddressUuids, true)) {
                return $this->buildErrorMessage(
                    sprintf(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND, $shippingAddressUuid),
                    CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND
                );
            }
        }

        return new RestErrorCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    protected function isLoggedCustomer(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        return $restCheckoutRequestAttributesTransfer->getRestUser()
            && $restCheckoutRequestAttributesTransfer->getRestUser()->getSurrogateIdentifier();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return string[]
     */
    protected function getCustomerShippingAddressUuids(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array
    {
        $restCheckoutRequestAttributesTransfer
            ->requireRestUser()
            ->getRestUser()
                ->requireSurrogateIdentifier();

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restCheckoutRequestAttributesTransfer->getRestUser()->getSurrogateIdentifier());

        $customerShippingAddressUuids = [];
        $addressesTransfer = $this->customerClient->getAddresses($customerTransfer);

        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $customerShippingAddressUuids[] = $addressTransfer->getUuid();
        }

        return $customerShippingAddressUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return string[]
     */
    protected function extractShippingAddressUuids(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array
    {
        $shippingAddressUuids = [];

        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            $restAddressTransfer = $restShipmentsTransfer->getShippingAddress();

            if ($restAddressTransfer && $restAddressTransfer->getId()) {
                $shippingAddressUuids[] = $restAddressTransfer->getId();
            }
        }

        return $shippingAddressUuids;
    }

    /**
     * @param string $detail
     * @param string $code
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function buildErrorMessage(
        string $detail,
        string $code,
        ?int $status = Response::HTTP_NOT_FOUND
    ): RestErrorCollectionTransfer {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setCode($code)
            ->setStatus($status);

        return (new RestErrorCollectionTransfer())
            ->addRestError($restErrorMessageTransfer);
    }
}
