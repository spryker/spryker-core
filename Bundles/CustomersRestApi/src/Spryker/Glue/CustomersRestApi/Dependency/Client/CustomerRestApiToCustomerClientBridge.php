<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerRestApiToCustomerClientBridge implements CustomerRestApiToCustomerClientInterface
{
    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     */
    public function __construct($customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return null|\Generated\Shared\Transfer\CustomerTransfer
     */
    public function findCustomerById(CustomerTransfer $customerTransfer): ?CustomerTransfer
    {
        return $this->customerClient->findCustomerById($customerTransfer);
    }

    /**
     * @return null|\Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomer(): ?CustomerTransfer
    {
        return $this->customerClient->getCustomer();
    }
}
