<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Business\Customer;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCustomerFacadeInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(CompanyUsersRestApiToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollection(
        CustomerCollectionTransfer $customerCollectionTransfer
    ): CustomerCollectionTransfer {
        foreach ($customerCollectionTransfer->getCustomers() as $customerTransfer) {
            $customerTransfer->fromArray(
                $this->customerFacade->findByReference($customerTransfer->getCustomerReference())->toArray(),
                true
            );
        }

        return $customerCollectionTransfer;
    }
}
