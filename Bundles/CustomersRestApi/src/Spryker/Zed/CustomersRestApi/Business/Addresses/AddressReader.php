<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business\Addresses;

use Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiRepositoryInterface;

class AddressReader implements AddressReaderInterface
{
    /**
     * @var \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiRepositoryInterface
     */
    protected $customersRestApiRepository;

    /**
     * @param \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiRepositoryInterface $customersRestApiRepository
     */
    public function __construct(CustomersRestApiRepositoryInterface $customersRestApiRepository)
    {
        $this->customersRestApiRepository = $customersRestApiRepository;
    }

    /**
     * @param string $addressUuid
     * @param int $idCustomer
     *
     * @return int|null
     */
    public function findCustomerIdCustomerAddressByUuid(string $addressUuid, int $idCustomer): ?int
    {
        return $this->customersRestApiRepository->findCustomerIdCustomerAddressByUuid($addressUuid, $idCustomer);
    }
}
