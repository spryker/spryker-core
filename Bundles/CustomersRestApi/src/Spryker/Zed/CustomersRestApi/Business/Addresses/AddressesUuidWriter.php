<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business\Addresses;

use Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiEntityManagerInterface;

class AddressesUuidWriter implements AddressesUuidWriterInterface
{
    /**
     * @var \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiEntityManagerInterface
     */
    protected $addressesEntityManager;

    /**
     * @param \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiEntityManagerInterface $addressesEntityManager
     */
    public function __construct(CustomersRestApiEntityManagerInterface $addressesEntityManager)
    {
        $this->addressesEntityManager = $addressesEntityManager;
    }

    /**
     * @return void
     */
    public function updateAddressesUuid(): void
    {
        $this->addressesEntityManager->updateAddressesWithoutUuid();
    }
}
