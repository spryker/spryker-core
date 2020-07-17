<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\RestAgentAccessTokensAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\AgentAuthRestApi\Processor\Mapper\AgentAccessTokenMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class AgentAccessTokenRestResponseBuilder implements AgentAccessTokenRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Processor\Mapper\AgentAccessTokenMapperInterface
     */
    protected $agentAccessTokenMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\AgentAuthRestApi\Processor\Mapper\AgentAccessTokenMapperInterface $agentAccessTokenMapper
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, AgentAccessTokenMapperInterface $agentAccessTokenMapper)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->agentAccessTokenMapper = $agentAccessTokenMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAgentAccessTokensRestResponse(OauthResponseTransfer $oauthResponseTransfer): RestResponseInterface
    {
        $restAgentAccessTokensAttributesTransfer = (new RestAgentAccessTokensAttributesTransfer())
            ->fromArray($oauthResponseTransfer->toArray(), true);

        $accessTokenResource = $this->restResourceBuilder
            ->createRestResource(
                AgentAuthRestApiConfig::RESOURCE_AGENT_ACCESS_TOKENS,
                null,
                $restAgentAccessTokensAttributesTransfer
            );

        return $this->restResourceBuilder->createRestResponse()
            ->addResource($accessTokenResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createInvalidCredentialsErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(AgentAuthRestApiConfig::RESPONSE_CODE_INVALID_LOGIN)
            ->setStatus(Response::HTTP_UNAUTHORIZED)
            ->setDetail(AgentAuthRestApiConfig::RESPONSE_DETAIL_INVALID_LOGIN);

        return $this->restResourceBuilder->createRestResponse()
            ->addError($restErrorMessageTransfer);
    }
}
