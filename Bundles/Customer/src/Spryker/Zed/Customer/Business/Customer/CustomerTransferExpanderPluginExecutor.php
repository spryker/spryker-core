<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerTransferExpanderPluginExecutor implements CustomerTransferExpanderPluginExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface[]
     */
    protected $customerTransferExpanderPlugins;

    /**
     * @param \Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface[] $customerTransferExpanderPlugins
     */
    public function __construct(array $customerTransferExpanderPlugins)
    {
        $this->customerTransferExpanderPlugins = $customerTransferExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function executeCustomerTransferExpanderPlugins(CustomerTransfer $customerTransfer)
    {
        foreach ($this->customerTransferExpanderPlugins as $customerTransferExpanderPlugin) {
            $customerTransfer = $customerTransferExpanderPlugin->expandTransfer($customerTransfer);
        }

        return $customerTransfer;
    }
}
