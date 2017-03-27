<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerToCustomerTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerToCustomer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

class CustomerApi
{

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $queryContainer
     */
    public function __construct(CustomerQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function get(ApiRequestTransfer $customerTransfer)
    {

        return $customerTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Customer\Persistence\SpyCustomerToCustomer[] $customerToCustomerCollection
     *
     * @return \Generated\Shared\Transfer\CustomerToCustomerTransfer[]
     */
    protected function entityCollectionToTransferCollection(ObjectCollection $customerToCustomerCollection)
    {
        $customers = new ArrayObject();

        foreach ($customerToCustomerCollection as $customerToCustomerEntity) {
            $customerToCustomerTransfer = new CustomerToCustomerTransfer();
            $customerToCustomerTransfer->fromArray($customerToCustomerEntity->toArray(), true);

            $customers[] = $customerToCustomerTransfer;
        }

        return $customers;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function add(ApiRequestTransfer $customerTransfer)
    {

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function update(CustomerTransfer $customerTransfer)
    {


        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return void
     */
    protected function saveCustomers(CustomerTransfer $customerTransfer, SpyCustomer $customerEntity)
    {
        foreach ($customerTransfer->getCustomers() as $customerTransfer) {
            $customerToCustomerEntity = new SpyCustomerToCustomer();
            $customerToCustomerEntity->setFkCustomer($customerEntity->getIdCustomer());
            $customerToCustomerEntity->setFkCustomer($customerTransfer->getFkCustomer());

            $customerToCustomerEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function delete(CustomerTransfer $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);
        $customerEntity->delete();

        return true;
    }

}
