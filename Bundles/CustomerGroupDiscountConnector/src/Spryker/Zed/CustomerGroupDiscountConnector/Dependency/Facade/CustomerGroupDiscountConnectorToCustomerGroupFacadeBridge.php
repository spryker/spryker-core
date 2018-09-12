<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade;

class CustomerGroupDiscountConnectorToCustomerGroupFacadeBridge implements CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface
{
    /**
     * @var \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface
     */
    protected $customerGroupFacade;

    /**
     * @param \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface $customerGroupFacade
     */
    public function __construct($customerGroupFacade)
    {
        $this->customerGroupFacade = $customerGroupFacade;
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function findCustomerGroupByIdCustomer($idCustomer)
    {
        return $this->customerGroupFacade
            ->findCustomerGroupByIdCustomer($idCustomer);
    }
}
