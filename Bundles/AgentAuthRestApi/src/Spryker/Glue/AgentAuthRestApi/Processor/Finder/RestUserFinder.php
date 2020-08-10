<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Finder;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToOauthServiceInterface;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToUtilEncodingServiceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestUserFinder implements RestUserFinderInterface
{
    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToOauthServiceInterface
     */
    protected $oauthService;

    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToOauthServiceInterface $oauthService
     * @param \Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        AgentAuthRestApiToOauthServiceInterface $oauthService,
        AgentAuthRestApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->oauthService = $oauthService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    public function findAgentRestUser(RestRequestInterface $restRequest): ?RestUserTransfer
    {
        $agentAccessToken = $restRequest->getHttpRequest()->headers->get(AgentAuthRestApiConfig::HEADER_X_AGENT_AUTHORIZATION);

        if (!$agentAccessToken) {
            return null;
        }

        $agentAccessToken = $this->extractToken($agentAccessToken);
        $agentAccessTokenType = $this->extractTokenType($agentAccessToken);

        if (!$agentAccessToken || !$agentAccessTokenType) {
            return null;
        }

        $oauthAccessTokenDataTransfer = $this->oauthService->extractAccessTokenData($agentAccessToken);
        $decodedOauthUserId = $this->utilEncodingService->decodeJson($oauthAccessTokenDataTransfer->getOauthUserId(), true);

        if ($decodedOauthUserId && isset($decodedOauthUserId['id_agent'])) {
            return (new RestUserTransfer())->setIdAgent($decodedOauthUserId['id_agent']);
        }

        return null;
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    protected function extractToken(string $authorizationToken): ?string
    {
        return preg_split('/\s+/', $authorizationToken)[1] ?? null;
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    protected function extractTokenType(string $authorizationToken): ?string
    {
        return preg_split('/\s+/', $authorizationToken)[0] ?? null;
    }
}
