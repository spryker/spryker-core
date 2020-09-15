<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Reader;

use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToOauthServiceInterface;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToUtilEncodingServiceInterface;

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
     * @param string $agentAccessTokenHeader
     *
     * @return int|null
     */
    public function getIdAgentFromOauthAccessToken(string $agentAccessTokenHeader): ?int
    {
        $agentAccessToken = $this->extractToken($agentAccessTokenHeader);
        $agentAccessTokenType = $this->extractTokenType($agentAccessTokenHeader);

        if (!$agentAccessToken || !$agentAccessTokenType) {
            return null;
        }

        $oauthAccessTokenDataTransfer = $this->oauthService->extractAccessTokenData($agentAccessToken);
        $decodedOauthUserId = $this->utilEncodingService->decodeJson($oauthAccessTokenDataTransfer->getOauthUserId(), true);

        return $decodedOauthUserId['id_agent'] ?? null;
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
