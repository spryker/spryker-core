<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi;

use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\AgentCustomerImpersonationAccessToken\AgentCustomerImpersonationAccessTokenCreator;
use Spryker\Glue\AgentAuthRestApi\Processor\AgentCustomerImpersonationAccessToken\AgentCustomerImpersonationAccessTokenCreatorInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentAccessTokenCreator;
use Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentAccessTokenCreatorInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Mapper\AgentAccessTokenMapper;
use Spryker\Glue\AgentAuthRestApi\Processor\Mapper\AgentAccessTokenMapperInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilder;
use Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class AgentAuthRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\Mapper\AgentAccessTokenMapperInterface
     */
    public function createAgentAccessTokenMapper(): AgentAccessTokenMapperInterface
    {
        return new AgentAccessTokenMapper();
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface
     */
    public function createAgentAccessTokenRestResponseBuilder(): AgentAccessTokenRestResponseBuilderInterface
    {
        return new AgentAccessTokenRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createAgentAccessTokenMapper()
        );
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentAccessTokenCreatorInterface
     */
    public function createAgentAccessTokenCreator(): AgentAccessTokenCreatorInterface
    {
        return new AgentAccessTokenCreator(
            $this->getOauthClient(),
            $this->createAgentAccessTokenRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\AgentCustomerImpersonationAccessToken\AgentCustomerImpersonationAccessTokenCreatorInterface
     */
    public function createAgentCustomerImpersonationAccessTokenCreator(): AgentCustomerImpersonationAccessTokenCreatorInterface
    {
        return new AgentCustomerImpersonationAccessTokenCreator(
            $this->getOauthClient(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface
     */
    public function getOauthClient(): AgentAuthRestApiToOauthClientInterface
    {
        return $this->getProvidedDependency(AgentAuthRestApiDependencyProvider::CLIENT_OAUTH);
    }
}
