<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\DefaultAddressChangePluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class CustomerAddressSessionUpdatePlugin extends AbstractPlugin implements DefaultAddressChangePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function process(CustomerTransfer $customerTransfer)
    {
        $this->getClient()->setCustomer($customerTransfer);
    }
}
