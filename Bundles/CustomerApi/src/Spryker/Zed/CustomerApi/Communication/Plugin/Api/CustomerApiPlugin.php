<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Spryker\Zed\Api\Dependency\Plugin\ApiPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerApi\Business\CustomerApiFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class CustomerApiPlugin extends AbstractPlugin implements ApiPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer[]
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFacade()->findCustomers($apiRequestTransfer);
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function get($idCustomer, ApiDataTransfer $apiDataTransfer)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        return $this->getFacade()->getCustomer($customerTransfer);
    }

    /**
     * @param array $customer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($apiDataTransfer->getData(), true);

        return $this->getFacade()->addCustomer($customerTransfer);
    }

    /**
     * @param int $idCustomer
     * @param array $customer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function update($idCustomer, ApiDataTransfer $apiDataTransfer)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($apiDataTransfer->getData(), true);
        $customerTransfer->setIdCustomer($idCustomer);

        return $this->getFacade()->updateCustomer($customerTransfer);
    }

    /**
     * @param int $idCustomer
     *
     * @return bool
     */
    public function delete($idCustomer)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        return $this->getFacade()->deleteCustomer($customerTransfer);
    }

}
