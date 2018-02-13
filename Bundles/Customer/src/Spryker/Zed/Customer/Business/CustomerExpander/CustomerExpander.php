<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerExpander;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerExpander implements CustomerExpanderInterface
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
    public function expand(CustomerTransfer $customerTransfer)
    {
        foreach ($this->customerTransferExpanderPlugins as $customerTransferExpanderPlugin) {
            $customerTransfer = $customerTransferExpanderPlugin->expandTransfer($customerTransfer);
        }

        return $customerTransfer;
    }
}
