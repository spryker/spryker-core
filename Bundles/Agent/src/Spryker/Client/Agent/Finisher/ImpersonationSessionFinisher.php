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
     * @param array<\Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationSessionFinisherPluginInterface> $impersonationSessionFinisherPlugins
     */
    public function __construct(
        protected AgentToCustomerClientInterface $customerClient,
        protected array $impersonationSessionFinisherPlugins
    ) {
    }

    public function finishImpersonationSession(): void
    {
        $this->executeImpersonationSessionFinisherPlugins();
        $this->customerClient->logout();
    }

    protected function executeImpersonationSessionFinisherPlugins(): void
    {
        foreach ($this->impersonationSessionFinisherPlugins as $impersonationSessionFinisherPlugin) {
            $impersonationSessionFinisherPlugin->finish();
        }
    }
}
