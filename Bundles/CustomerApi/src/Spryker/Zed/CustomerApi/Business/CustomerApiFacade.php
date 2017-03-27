<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerApi\Business\CustomerApiBusinessFactory getFactory()
 */
class CustomerApiFacade extends AbstractFacade implements CustomerApiFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer[]
     */
    public function findCustomers(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->find($apiRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomer(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->add($apiRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     */
    public function getCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->get($customerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->update($customerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->delete($customerTransfer);
    }

}
