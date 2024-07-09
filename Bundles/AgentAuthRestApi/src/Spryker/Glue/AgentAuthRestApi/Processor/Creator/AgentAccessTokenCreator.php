<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Creator;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\RestAgentAccessTokensRequestAttributesTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Logger\AuditLoggerInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AgentAccessTokenCreator implements AgentAccessTokenCreatorInterface
{
    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface
     */
    protected $agentAccessTokenRestResponseBuilder;

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Processor\Logger\AuditLoggerInterface
     */
    protected AuditLoggerInterface $auditLogger;

    /**
     * @param \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface $oauthClient
     * @param \Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface $agentAccessTokenRestResponseBuilder
     * @param \Spryker\Glue\AgentAuthRestApi\Processor\Logger\AuditLoggerInterface $auditLogger
     */
    public function __construct(
        AgentAuthRestApiToOauthClientInterface $oauthClient,
        AgentAccessTokenRestResponseBuilderInterface $agentAccessTokenRestResponseBuilder,
        AuditLoggerInterface $auditLogger
    ) {
        $this->oauthClient = $oauthClient;
        $this->agentAccessTokenRestResponseBuilder = $agentAccessTokenRestResponseBuilder;
        $this->auditLogger = $auditLogger;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestAgentAccessTokensRequestAttributesTransfer $restAgentAccessTokensRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAccessToken(
        RestRequestInterface $restRequest,
        RestAgentAccessTokensRequestAttributesTransfer $restAgentAccessTokensRequestAttributesTransfer
    ): RestResponseInterface {
        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->fromArray($restAgentAccessTokensRequestAttributesTransfer->toArray(), true)
            ->setGrantType(AgentAuthRestApiConfig::GRANT_TYPE_AGENT_CREDENTIALS);

        $oauthResponseTransfer = $this->oauthClient->processAccessTokenRequest($oauthRequestTransfer);

        if (!$oauthResponseTransfer->getIsValid()) {
            $this->auditLogger->addAgentFailedLoginAuditLog($oauthRequestTransfer);

            return $this->agentAccessTokenRestResponseBuilder->createInvalidCredentialsErrorResponse();
        }

        $this->auditLogger->addAgentSuccessfulLoginAuditLog($oauthRequestTransfer);

        return $this->agentAccessTokenRestResponseBuilder->createAgentAccessTokensRestResponse($oauthResponseTransfer);
    }
}
