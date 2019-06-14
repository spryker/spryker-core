<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Persistence\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;

interface CustomerMapperInterface
{
    /**
     * @param array $customer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapCustomerEntityToCustomer(array $customer): CustomerTransfer;

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerAddress $customerAddressEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapCustomerAddressEntityToTransfer(SpyCustomerAddress $customerAddressEntity): AddressTransfer;
}
