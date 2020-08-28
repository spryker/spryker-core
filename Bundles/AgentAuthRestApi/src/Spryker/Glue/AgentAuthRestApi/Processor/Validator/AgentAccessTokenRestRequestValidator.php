<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Validator;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Reader\AgentAuthorizationHeaderReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentAccessTokenRestRequestValidator implements AgentAccessTokenRestRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Processor\Reader\AgentAuthorizationHeaderReaderInterface
     */
    protected $agentAuthorizationHeaderReader;

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @param \Spryker\Glue\AgentAuthRestApi\Processor\Reader\AgentAuthorizationHeaderReaderInterface $agentAuthorizationHeaderReader
     * @param \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface $oauthClient
     */
    public function __construct(
        AgentAuthorizationHeaderReaderInterface $agentAuthorizationHeaderReader,
        AgentAuthRestApiToOauthClientInterface $oauthClient
    ) {
        $this->agentAuthorizationHeaderReader = $agentAuthorizationHeaderReader;
        $this->oauthClient = $oauthClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $request, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $agentAccessTokenHeader = $request->headers->get(AgentAuthRestApiConfig::HEADER_X_AGENT_AUTHORIZATION);

        if (!$agentAccessTokenHeader) {
            return null;
        }

        $accessToken = $this->agentAuthorizationHeaderReader->extractToken($agentAccessTokenHeader);
        $accessTokenType = $this->agentAuthorizationHeaderReader->extractTokenType($agentAccessTokenHeader);

        if (!$accessToken || !$accessTokenType) {
            return null;
        }

        $oauthAccessTokenValidationRequestTransfer = (new OauthAccessTokenValidationRequestTransfer())
            ->setAccessToken($accessToken)
            ->setType($accessTokenType);

        $authAccessTokenValidationResponseTransfer = $this->oauthClient
            ->validateAccessToken($oauthAccessTokenValidationRequestTransfer);

        if (!$authAccessTokenValidationResponseTransfer->getIsValid()) {
            return (new RestErrorCollectionTransfer())
                ->addRestError(
                    (new RestErrorMessageTransfer())
                        ->setDetail(AgentAuthRestApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN)
                        ->setStatus(Response::HTTP_UNAUTHORIZED)
                        ->setCode(AgentAuthRestApiConfig::RESPONSE_CODE_INVALID_ACCESS_TOKEN)
                );
        }

        return null;
    }
}
