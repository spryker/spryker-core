<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Table\CustomerTablePluginExecutor;

use Generated\Shared\Transfer\CustomerTransfer;

/**
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class CustomerTablePluginExecutor implements CustomerTablePluginExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionPluginInterface[]
     */
    protected $customerTablePlugins;

    /**
     * @param \Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionPluginInterface[] $customerTablePlugins
     */
    public function __construct(array $customerTablePlugins)
    {
        $this->customerTablePlugins = $customerTablePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string[] $buttons
     *
     * @return string[]
     */
    public function execute(CustomerTransfer $customerTransfer, array $buttons): array
    {
        foreach ($this->customerTablePlugins as $customerTablePlugin) {
            $buttons = $customerTablePlugin->execute($customerTransfer, $buttons);
        }

        return $buttons;
    }
}
