<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Business;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupBusinessFactory getFactory()
 */
class CustomerGroupFacade extends AbstractFacade implements CustomerGroupFacadeInterface
{

    /**
     * @api
     * {@inheritdoc}
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
     * @api
     * {@inheritdoc}
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
     * @api
     * {@inheritdoc}
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
     * @api
     * {@inheritdoc}
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
     * @api
     * {@inheritdoc}
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

}
