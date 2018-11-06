<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Dependency\Facade;

use Generated\Shared\Transfer\AddressTransfer;

interface CheckoutRestApiToCustomersRestApiFacadeInterface
{
    /**
     * @param string $addressUuid
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findCustomerAddressById(string $addressUuid, int $idCustomer): ?AddressTransfer;
}
