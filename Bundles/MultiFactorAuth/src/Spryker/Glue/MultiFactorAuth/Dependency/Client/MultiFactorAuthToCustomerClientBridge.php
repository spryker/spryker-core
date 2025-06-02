<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

class MultiFactorAuthToCustomerClientBridge implements MultiFactorAuthToCustomerClientInterface
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
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerById(int $idCustomer): CustomerTransfer
    {
        return $this->customerClient->getCustomerById($idCustomer);
    }
}
