<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Executor;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerPluginExecutor implements CustomerPluginExecutorInterface
{
    /**
     * @var list<\Spryker\Zed\CustomerExtension\Dependency\Plugin\PostCustomerRegistrationPluginInterface>
     */
    protected array $postCustomerRegistrationPlugins;

    /**
     * @var list<\Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPostDeletePluginInterface>
     */
    protected array $customerPostDeletePlugins;

    /**
     * @param list<\Spryker\Zed\CustomerExtension\Dependency\Plugin\PostCustomerRegistrationPluginInterface> $postCustomerRegistrationPlugins
     * @param list<\Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPostDeletePluginInterface> $customerPostDeletePlugins
     */
    public function __construct(
        array $postCustomerRegistrationPlugins = [],
        array $customerPostDeletePlugins = []
    ) {
        $this->postCustomerRegistrationPlugins = $postCustomerRegistrationPlugins;
        $this->customerPostDeletePlugins = $customerPostDeletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function executePostCustomerRegistrationPlugins(CustomerTransfer $customerTransfer): void
    {
        foreach ($this->postCustomerRegistrationPlugins as $postCustomerRegistrationPlugin) {
            $postCustomerRegistrationPlugin->execute($customerTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function executeCustomerPostDeletePlugins(CustomerTransfer $customerTransfer): void
    {
        foreach ($this->customerPostDeletePlugins as $customerPostDeletePlugin) {
            $customerPostDeletePlugin->execute($customerTransfer);
        }
    }
}
