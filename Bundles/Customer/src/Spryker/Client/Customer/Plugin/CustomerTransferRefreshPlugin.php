<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\CustomerClient;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method CustomerClient getClient()
 */
class CustomerTransferRefreshPlugin extends AbstractPlugin implements CustomerSessionGetPluginInterface
{

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function executes(CustomerTransfer $customerTransfer)
    {
        if ($customerTransfer && $customerTransfer->getIsDirty()) {
            $customerTransfer = $this->getClient()->getCustomerById($customerTransfer->getIdCustomer());
            $this->getClient()->setCustomer($customerTransfer);
        }
    }
}
