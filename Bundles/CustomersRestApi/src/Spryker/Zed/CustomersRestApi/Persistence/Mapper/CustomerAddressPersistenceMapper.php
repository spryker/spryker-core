<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Persistence\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\SpyCustomerAddressEntityTransfer;

class CustomerAddressPersistenceMapper implements CustomerAddressPersistenceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCustomerAddressEntityTransfer $customerAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function mapCustomerAddressEntityTransferToAddressTransfer(SpyCustomerAddressEntityTransfer $customerAddressEntityTransfer): ?AddressTransfer
    {
        $addressTransfer = (new AddressTransfer())->fromArray($customerAddressEntityTransfer->toArray(), true);
        $addressTransfer->setIso2Code($customerAddressEntityTransfer->getCountry()->getIso2Code());

        return $addressTransfer;
    }
}
