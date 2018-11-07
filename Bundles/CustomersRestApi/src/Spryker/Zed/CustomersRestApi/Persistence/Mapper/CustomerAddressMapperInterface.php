<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Persistence\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\SpyCustomerAddressEntityTransfer;

interface CustomerAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCustomerAddressEntityTransfer $customerAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function mapCustomerAddressEntityTransferToAddressTransfer(SpyCustomerAddressEntityTransfer $customerAddress): ?AddressTransfer;
}
