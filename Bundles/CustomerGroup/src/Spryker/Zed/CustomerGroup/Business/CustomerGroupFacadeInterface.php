<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Business;

use Generated\Shared\Transfer\CustomerGroupCollectionTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerGroupFacadeInterface
{
    /**
     * Specification:
     *  - Adds new group
     *  - If list of customers is not empty, assigns customers, specified in customers array to group
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function add(CustomerGroupTransfer $customerGroupTransfer);

    /**
     * Specification:
     *  - Finds customer group by customer group ID
     *  - Throws CustomerGroupNotFoundException if not found
     *  - Incoming CustomerGroupTransfer is modified with data from DB
     *  - If group has customers assigned, they are fetched and returned in Customers property
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @throws \Spryker\Zed\CustomerGroup\Business\Exception\CustomerGroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function get(CustomerGroupTransfer $customerGroupTransfer);

    /**
     * Specification:
     *  - Finds customer group by customer group ID
     *  - Throws CustomerGroupNotFoundException if not found
     *  - Entity is modified with data from CustomerGroupTransfer and saved
     *  - All assigned customers are deleted, customers specified in getCustomers are assigned to the group
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @throws \Spryker\Zed\CustomerGroup\Business\Exception\CustomerGroupNotFoundException
     *
     * @return void
     */
    public function update(CustomerGroupTransfer $customerGroupTransfer);

    /**
     * Specification:
     *  - Finds customer group by customer group ID
     *  - Throws CustomerGroupNotFoundException if not found
     *  - Deletes customer group
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @throws \Spryker\Zed\CustomerGroup\Business\Exception\CustomerGroupNotFoundException
     *
     * @return void
     */
    public function delete(CustomerGroupTransfer $customerGroupTransfer);

    /**
     * Specification:
     *  - If getCustomers is empty, does nothing
     *  - Finds customers from getCustomers assigned to the group and unassigns them
     *  - If costomer is not assign to the group, skips
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function removeCustomersFromGroup(CustomerGroupTransfer $customerGroupTransfer);

    /**
     * Specification:
     *  - Finds Customer group by given customer id
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function findCustomerGroupByIdCustomer($idCustomer);

    /**
     * Specification:
     *  - Retrieves all customer groups by customer id
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupCollectionTransfer
     */
    public function getCustomerGroupCollectionByIdCustomer(int $idCustomer): CustomerGroupCollectionTransfer;

    /**
     * Specification:
     *  - Finds all groups which a customer participates
     *  - Removes each customer group in the found batch
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function removeCustomerFromAllGroups(CustomerTransfer $customerTransfer);
}
