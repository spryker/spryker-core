<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency\Facade;

use Generated\Shared\Transfer\AddressTransfer;

interface ShipmentToCustomerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function findCustomerAddressByAddressData(AddressTransfer $addressTransfer): ?AddressTransfer;
}
