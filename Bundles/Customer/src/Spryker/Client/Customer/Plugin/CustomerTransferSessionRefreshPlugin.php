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
class CustomerTransferSessionRefreshPlugin extends AbstractPlugin implements CustomerSessionGetPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executed if customer marked as dirty.
     * - Marks customer as not dirty.
     * - Retrieves customer by either provided id, email or restore password key.
     * - Expands the provided CustomerTransfer with persistence and stores it to session.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        if ($customerTransfer->getIsDirty()) {
            $customerTransfer = $this->getClient()->getCustomerByEmail($customerTransfer);
            $customerTransfer->setIsDirty(false);
            $this->getClient()->setCustomer($customerTransfer);
        }
    }
}
