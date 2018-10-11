<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Table\PluginExecutor;

/**
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class CustomerTableExpanderPluginExecutor implements CustomerTableExpanderPluginExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionExpanderPluginInterface[]
     */
    protected $customerTableActionExpanderPlugins;

    /**
     * @param \Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionExpanderPluginInterface[] $customerTableActionExpanderPlugins
     */
    public function __construct(array $customerTableActionExpanderPlugins)
    {
        $this->customerTableActionExpanderPlugins = $customerTableActionExpanderPlugins;
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeActionExpanderPlugins(int $idCustomer): array
    {
        $buttons = [];

        foreach ($this->customerTableActionExpanderPlugins as $customerTableActionExpanderPlugin) {
            $buttons = $customerTableActionExpanderPlugin->execute($idCustomer, $buttons);
        }

        return $buttons;
    }
}
