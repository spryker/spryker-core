<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent;

use Spryker\Client\Agent\Dependency\Client\AgentToSessionClientInterface;
use Spryker\Client\Agent\Dependency\Client\AgentToZedRequestClientInterface;
use Spryker\Client\Agent\Sanitizer\CustomerImpersonationSanitizer;
use Spryker\Client\Agent\Sanitizer\CustomerImpersonationSanitizerInterface;
use Spryker\Client\Agent\Session\AgentSession;
use Spryker\Client\Agent\Session\AgentSessionInterface;
use Spryker\Client\Agent\Zed\AgentStub;
use Spryker\Client\Agent\Zed\AgentStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AgentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Agent\Zed\AgentStubInterface
     */
    public function createZedStub(): AgentStubInterface
    {
        return new AgentStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\Agent\Session\AgentSessionInterface
     */
    public function createAgentSession(): AgentSessionInterface
    {
        return new AgentSession(
            $this->getSessionClient()
        );
    }

    /**
     * @return \Spryker\Client\Agent\Sanitizer\CustomerImpersonationSanitizerInterface
     */
    public function createCustomerImpersonationSanitizer(): CustomerImpersonationSanitizerInterface
    {
        return new CustomerImpersonationSanitizer(
            $this->getCustomerImpersonationSanitizerPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Agent\Dependency\Client\AgentToZedRequestClientInterface
     */
    public function getZedRequestClient(): AgentToZedRequestClientInterface
    {
        return $this->getProvidedDependency(AgentDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\Agent\Dependency\Client\AgentToSessionClientInterface
     */
    public function getSessionClient(): AgentToSessionClientInterface
    {
        return $this->getProvidedDependency(AgentDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\AgentExtension\Dependency\Plugin\CustomerImpersonationSanitizerPluginInterface[]
     */
    public function getCustomerImpersonationSanitizerPlugins(): array
    {
        return $this->getProvidedDependency(AgentDependencyProvider::PLUGINS_CUSTOMER_IMPERSONATION_SANITIZER);
    }
}
