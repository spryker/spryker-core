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
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentAccessTokenRestRequestValidator implements AgentAccessTokenRestRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @param \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface $oauthClient
     */
    public function __construct(AgentAuthRestApiToOauthClientInterface $oauthClient)
    {
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
        $authorizationToken = $request->headers->get(AgentAuthRestApiConfig::HEADER_X_AGENT_AUTHORIZATION);

        if (!$authorizationToken) {
            return null;
        }

        if ($this->validateAccessToken($authorizationToken)) {
            return null;
        }

        return (new RestErrorCollectionTransfer())
            ->addRestError(
                (new RestErrorMessageTransfer())
                    ->setDetail(AgentAuthRestApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN)
                    ->setStatus(Response::HTTP_UNAUTHORIZED)
                    ->setCode(AgentAuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID)
            );
    }

    /**
     * @param string $authorizationToken
     *
     * @return bool
     */
    protected function validateAccessToken(string $authorizationToken): bool
    {
        $accessToken = $this->extractToken($authorizationToken);
        $type = $this->extractTokenType($authorizationToken);

        if (!$accessToken || !$type) {
            return false;
        }

        $authAccessTokenValidationRequestTransfer = new OauthAccessTokenValidationRequestTransfer();
        $authAccessTokenValidationRequestTransfer
            ->setAccessToken($accessToken)
            ->setType($type);

        $authAccessTokenValidationResponseTransfer = $this->oauthClient->validateAccessToken($authAccessTokenValidationRequestTransfer);

        return $authAccessTokenValidationResponseTransfer->getIsValid();
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
