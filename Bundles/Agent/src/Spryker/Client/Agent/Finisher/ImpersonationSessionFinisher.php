<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Finisher;

use Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientInterface;

class ImpersonationSessionFinisher implements ImpersonationSessionFinisherInterface
{
    /**
     * @var \Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationSessionFinisherPluginInterface[]
     */
    protected $impersonationSessionFinisherPlugins;

    /**
     * @param \Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientInterface $customerClient
     * @param \Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationSessionFinisherPluginInterface[] $impersonationSessionFinisherPlugins
     */
    public function __construct(
        AgentToCustomerClientInterface $customerClient,
        array $impersonationSessionFinisherPlugins
    ) {
        $this->customerClient = $customerClient;
        $this->impersonationSessionFinisherPlugins = $impersonationSessionFinisherPlugins;
    }

    /**
     * @return void
     */
    public function finishImpersonationSession(): void
    {
        $this->executeImpersonationSessionFinisherPlugins();
        $this->customerClient->logout();
    }

    /**
     * @return void
     */
    protected function executeImpersonationSessionFinisherPlugins(): void
    {
        foreach ($this->impersonationSessionFinisherPlugins as $impersonationSessionFinisherPlugin) {
            $impersonationSessionFinisherPlugin->finish();
        }
    }
}
