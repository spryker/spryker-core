<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Business\CustomerGroup;

use Generated\Shared\Transfer\CustomerGroupsTransfer;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;

class CustomerGroupFinder implements CustomerGroupFinderInterface
{
    /**
     * @var \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $customerGroupQueryContainer;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $customerGroupQueryContainer
     */
    public function __construct(CustomerGroupQueryContainerInterface $customerGroupQueryContainer)
    {
        $this->customerGroupQueryContainer = $customerGroupQueryContainer;
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupsTransfer
     */
    public function findCustomerGroupsByIdCustomer(int $idCustomer): CustomerGroupsTransfer
    {
        // TODO: Implement findCustomerGroupsByIdCustomer() method.
        return new CustomerGroupsTransfer();
    }
}
