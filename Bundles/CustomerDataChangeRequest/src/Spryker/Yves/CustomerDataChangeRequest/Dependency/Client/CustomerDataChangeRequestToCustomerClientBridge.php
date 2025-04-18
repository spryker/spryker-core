<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CustomerDataChangeRequest\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerDataChangeRequestToCustomerClientBridge implements CustomerDataChangeRequestToCustomerClientInterface
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
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerRawData(): ?CustomerTransfer
    {
        return $this->customerClient->findCustomerRawData();
    }

    /**
     * @return void
     */
    public function markCustomerAsDirty(): void
    {
        $this->customerClient->markCustomerAsDirty();
    }
}
