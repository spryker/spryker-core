<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Finisher;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientInterface;

class ImpersonationFinisher implements ImpersonationFinisherInterface
{
    /**
     * @var \Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationFinisherPluginInterface[]
     */
    protected $impersonationFinisherPlugins;

    /**
     * @param \Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientInterface $customerClient
     * @param \Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationFinisherPluginInterface[] $impersonationFinisherPlugins
     */
    public function __construct(
        AgentToCustomerClientInterface $customerClient,
        array $impersonationFinisherPlugins
    ) {
        $this->impersonationFinisherPlugins = $impersonationFinisherPlugins;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function finishImpersonation(CustomerTransfer $customerTransfer): void
    {
        $this->customerClient->logout();

        $this->executeImpersonationFinisherPlugins($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function executeImpersonationFinisherPlugins(CustomerTransfer $customerTransfer): void
    {
        foreach ($this->impersonationFinisherPlugins as $impersonationFinisherPlugin) {
            $impersonationFinisherPlugin->finish($customerTransfer);
        }
    }
}
