<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Reader;

use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToOauthServiceInterface;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToUtilEncodingServiceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AgentAuthorizationHeaderReader implements AgentAuthorizationHeaderReaderInterface
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
     * @phpstan-return array<int|string>|null
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array|null
     */
    public function getDecodedOauthUserIdentifier(RestRequestInterface $restRequest): ?array
    {
        $agentAccessTokenHeader = $restRequest->getHttpRequest()->headers->get(AgentAuthRestApiConfig::HEADER_X_AGENT_AUTHORIZATION);

        if (!$agentAccessTokenHeader) {
            return null;
        }

        $agentAccessToken = $this->extractToken($agentAccessTokenHeader);
        $agentAccessTokenType = $this->extractTokenType($agentAccessTokenHeader);

        if (!$agentAccessToken || !$agentAccessTokenType) {
            return null;
        }

        $oauthAccessTokenDataTransfer = $this->oauthService->extractAccessTokenData($agentAccessToken);

        return $this->utilEncodingService->decodeJson($oauthAccessTokenDataTransfer->getOauthUserId(), true);
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    public function extractToken(string $authorizationToken): ?string
    {
        return preg_split('/\s+/', $authorizationToken)[1] ?? null;
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    public function extractTokenType(string $authorizationToken): ?string
    {
        return preg_split('/\s+/', $authorizationToken)[0] ?? null;
    }
}
