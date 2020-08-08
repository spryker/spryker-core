<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\RestAgentAccessTokensAttributesTransfer;
use Generated\Shared\Transfer\RestAgentCustomerImpersonationAccessTokensAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
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
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAgentCustomerImpersonationAccessTokensRestResponse(OauthResponseTransfer $oauthResponseTransfer): RestResponseInterface
    {
        $restAgentCustomerImpersonationAccessTokensAttributesTransfer = (new RestAgentCustomerImpersonationAccessTokensAttributesTransfer())
            ->fromArray($oauthResponseTransfer->toArray(), true);

        $accessTokenResource = $this->restResourceBuilder
            ->createRestResource(
                AgentAuthRestApiConfig::RESOURCE_AGENT_CUSTOMER_IMPERSONATION_ACCESS_TOKENS,
                null,
                $restAgentCustomerImpersonationAccessTokensAttributesTransfer
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

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedToImpersonateCustomerErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(AgentAuthRestApiConfig::RESPONSE_CODE_FAILED_TO_IMPERSONATE_CUSTOMER)
            ->setStatus(Response::HTTP_UNAUTHORIZED)
            ->setDetail(AgentAuthRestApiConfig::RESPONSE_DETAIL_FAILED_TO_IMPERSONATE_CUSTOMER);

        return $this->restResourceBuilder->createRestResponse()
            ->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createActionAvailableForAgentsOnlyErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(AgentAuthRestApiConfig::RESPONSE_CODE_AGENT_ONLY)
            ->setStatus(Response::HTTP_UNAUTHORIZED)
            ->setDetail(AgentAuthRestApiConfig::RESPONSE_DETAIL_AGENT_ONLY);

        return $this->restResourceBuilder->createRestResponse()
            ->addError($restErrorMessageTransfer);
    }
}
