<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi;

use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToOauthServiceInterface;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToUtilEncodingServiceInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentAccessTokenCreator;
use Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentAccessTokenCreatorInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentCustomerImpersonationAccessTokenCreator;
use Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentCustomerImpersonationAccessTokenCreatorInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Mapper\RestUserMapper;
use Spryker\Glue\AgentAuthRestApi\Processor\Mapper\RestUserMapperInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilder;
use Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Validator\AgentAccessTokenRestRequestValidator;
use Spryker\Glue\AgentAuthRestApi\Processor\Validator\AgentAccessTokenRestRequestValidatorInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Validator\AgentValidator;
use Spryker\Glue\AgentAuthRestApi\Processor\Validator\AgentValidatorInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig getConfig()
 */
class AgentAuthRestApiFactory extends AbstractFactory
{
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
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentCustomerImpersonationAccessTokenCreatorInterface
     */
    public function createAgentCustomerImpersonationAccessTokenCreator(): AgentCustomerImpersonationAccessTokenCreatorInterface
    {
        return new AgentCustomerImpersonationAccessTokenCreator(
            $this->getOauthClient(),
            $this->createAgentAccessTokenRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\Validator\AgentValidatorInterface
     */
    public function createAgentValidator(): AgentValidatorInterface
    {
        return new AgentValidator($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\Validator\AgentAccessTokenRestRequestValidatorInterface
     */
    public function createAgentAccessTokenRestRequestValidator(): AgentAccessTokenRestRequestValidatorInterface
    {
        return new AgentAccessTokenRestRequestValidator($this->getOauthClient());
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface
     */
    public function createAgentAccessTokenRestResponseBuilder(): AgentAccessTokenRestResponseBuilderInterface
    {
        return new AgentAccessTokenRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\Mapper\RestUserMapperInterface
     */
    public function createRestUserMapper(): RestUserMapperInterface
    {
        return new RestUserMapper(
            $this->getOauthService(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface
     */
    public function getOauthClient(): AgentAuthRestApiToOauthClientInterface
    {
        return $this->getProvidedDependency(AgentAuthRestApiDependencyProvider::CLIENT_OAUTH);
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToOauthServiceInterface
     */
    public function getOauthService(): AgentAuthRestApiToOauthServiceInterface
    {
        return $this->getProvidedDependency(AgentAuthRestApiDependencyProvider::SERVICE_OAUTH);
    }

    /**
     * @return \Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): AgentAuthRestApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AgentAuthRestApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
