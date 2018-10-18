<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class CustomerTransferSessionGetPlugin extends AbstractPlugin implements CustomerSessionGetPluginInterface
{
    /**
     * {@inheritdoc}
     * - Retrieves customer by either provided id, email or restore password key.
     * - Retrieves the fresh customer data from persistence and keeps the existing session only data.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        if ($customerTransfer && $customerTransfer->getIsDirty()) {
            $customerTransfer = $this->getClient()->getCustomerByEmail($customerTransfer);
            $this->getClient()->setCustomer($customerTransfer);
        }
    }
}
