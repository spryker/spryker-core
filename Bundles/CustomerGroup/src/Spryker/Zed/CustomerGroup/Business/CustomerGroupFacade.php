<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Business;

use Generated\Shared\Transfer\CustomerGroupsTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupBusinessFactory getFactory()
 */
class CustomerGroupFacade extends AbstractFacade implements CustomerGroupFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function add(CustomerGroupTransfer $customerGroupTransfer)
    {
        return $this->getFactory()
            ->createCustomerGroup()
            ->add($customerGroupTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     */
    public function get(CustomerGroupTransfer $customerGroupTransfer)
    {
        return $this->getFactory()
            ->createCustomerGroup()
            ->get($customerGroupTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function update(CustomerGroupTransfer $customerGroupTransfer)
    {
        $this->getFactory()
            ->createCustomerGroup()
            ->update($customerGroupTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function delete(CustomerGroupTransfer $customerGroupTransfer)
    {
        $this->getFactory()
            ->createCustomerGroup()
            ->delete($customerGroupTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function removeCustomersFromGroup(CustomerGroupTransfer $customerGroupTransfer)
    {
        $this->getFactory()
            ->createCustomerGroup()
            ->removeCustomersFromGroup($customerGroupTransfer);
    }

    /**
     * @deprecated Please use findCustomerGroupsByIdCustomer instead
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function findCustomerGroupByIdCustomer($idCustomer)
    {
        return $this->getFactory()
            ->createCustomerGroup()
            ->findCustomerGroupByIdCustomer($idCustomer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupsTransfer
     */
    public function findCustomerGroupsByIdCustomer(int $idCustomer): CustomerGroupsTransfer
    {
        return $this->getFactory()
            ->createCustomerGroupFinder()
            ->findCustomerGroupsByIdCustomer($idCustomer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function removeCustomerFromAllGroups(CustomerTransfer $customerTransfer)
    {
        $this->getFactory()
            ->createCustomerGroup()
            ->removeCustomerFromAllGroups($customerTransfer);
    }
}
