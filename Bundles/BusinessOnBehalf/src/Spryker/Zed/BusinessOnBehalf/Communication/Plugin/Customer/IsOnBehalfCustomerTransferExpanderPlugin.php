<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Communication\Plugin\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Business\BusinessOnBehalfFacadeInterface getFacade()
 * @method \Spryker\Zed\BusinessOnBehalf\BusinessOnBehalfConfig getConfig()
 */
class IsOnBehalfCustomerTransferExpanderPlugin extends AbstractPlugin implements CustomerTransferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandTransfer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->getFacade()->expandCustomerWithIsOnBehalf($customerTransfer);
    }
}
