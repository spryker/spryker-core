<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestAddressAttributesTransfer;

class AddressResourceMapper implements AddressResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\RestAddressAttributesTransfer
     */
    public function mapAddressTransferToRestAddressAttributesTransfer(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customerTransfer
    ): RestAddressAttributesTransfer {
        return (new RestAddressAttributesTransfer())
            ->fromArray($addressTransfer->toArray(), true)
            ->setCountry($addressTransfer->getCountry()->getName())
            ->setIsDefaultShipping($customerTransfer->getDefaultShippingAddress() === $addressTransfer->getIdCustomerAddress())
            ->setIsDefaultBilling($customerTransfer->getDefaultBillingAddress() === $addressTransfer->getIdCustomerAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $restAddressAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapRestAddressAttributesTransferToAddressTransfer(
        RestAddressAttributesTransfer $restAddressAttributesTransfer
    ): AddressTransfer {
        return (new AddressTransfer())->fromArray($restAddressAttributesTransfer->toArray(), true);
    }
}
