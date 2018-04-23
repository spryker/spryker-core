<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Zed\Customer\Persistence\CustomerEntityManagerInterface;
use Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerEntityManagerInterface
     */
    protected $customerEntityManager;

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\AddressInterface
     */
    protected $addressManager;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerEntityManagerInterface $customerEntityManager
     * @param \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface $customerRepository
     * @param \Spryker\Zed\Customer\Business\Customer\AddressInterface $addressManager
     */
    public function __construct(
        CustomerEntityManagerInterface $customerEntityManager,
        CustomerRepositoryInterface $customerRepository,
        AddressInterface $addressManager
    ) {
        $this->customerEntityManager = $customerEntityManager;
        $this->customerRepository = $customerRepository;
        $this->addressManager = $addressManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollection(CustomerCollectionTransfer $customerCollectionTransfer): CustomerCollectionTransfer
    {
        $customerCollectionTransfer = $this->customerRepository->getCustomerCollection($customerCollectionTransfer);
        $customerCollectionTransfer = $this->hydrateCustomersWithAddresses($customerCollectionTransfer);

        return $customerCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerListTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    protected function hydrateCustomersWithAddresses(CustomerCollectionTransfer $customerListTransfer): CustomerCollectionTransfer
    {
        foreach ($customerListTransfer->getCustomers() as $customerTransfer) {
            $customerTransfer->setAddresses($this->addressManager->getAddresses($customerTransfer));
        }

        return $customerListTransfer;
    }
}
