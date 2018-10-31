<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business\Addresses;

interface AddressReaderInterface
{
    /**
     * @param string $addressUuid
     * @param int $idCustomer
     *
     * @return int|null
     */
    public function findCustomerIdCustomerAddressByUuid(string $addressUuid, int $idCustomer): ?int;
}
