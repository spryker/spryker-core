<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Dependency\Facade;

class CheckoutRestApiToCustomersRestApiFacadeBridge implements CheckoutRestApiToCustomersRestApiFacadeInterface
{
    /**
     * @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacadeInterface
     */
    protected $customersRestApiFacade;

    /**
     * @param \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacadeInterface $customersRestApiFacade
     */
    public function __construct($customersRestApiFacade)
    {
        $this->customersRestApiFacade = $customersRestApiFacade;
    }

    /**
     * @param string $addressUuid
     * @param int $idCustomer
     *
     * @return int|null
     */
    public function findCustomerIdCustomerAddressByUuid(string $addressUuid, int $idCustomer): ?int
    {
        return $this->customersRestApiFacade->findCustomerIdCustomerAddressByUuid($addressUuid, $idCustomer);
    }
}
