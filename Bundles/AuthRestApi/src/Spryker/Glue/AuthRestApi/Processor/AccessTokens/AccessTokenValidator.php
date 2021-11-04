<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenValidator implements AccessTokenValidatorInterface
{
    /**
     * @var string
     */
    protected const REQUEST_ATTRIBUTE_IS_PROTECTED = 'is-protected';

    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @param \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface $oauthClient
     */
    public function __construct(AuthRestApiToOauthClientInterface $oauthClient)
    {
        $this->oauthClient = $oauthClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        $isProtected = $request->attributes->get(static::REQUEST_ATTRIBUTE_IS_PROTECTED, false);

        if (!$isProtected) {
            return null;
        }

        $authorizationToken = $request->headers->get(AuthRestApiConfig::HEADER_AUTHORIZATION);
        if (!$authorizationToken) {
            return $this->createErrorMessageTransfer(
                AuthRestApiConfig::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN,
                Response::HTTP_FORBIDDEN,
                AuthRestApiConfig::RESPONSE_CODE_FORBIDDEN,
            );
        }

        if (!$this->validateAccessToken($authorizationToken)) {
            return $this->createErrorMessageTransfer(
                AuthRestApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN,
                Response::HTTP_UNAUTHORIZED,
                AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID,
            );
        }

        return null;
    }

    /**
     * @param string $detail
     * @param int $status
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(
        string $detail,
        int $status,
        string $code
    ): RestErrorMessageTransfer {
        return (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setStatus($status)
            ->setCode($code);
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

        return $this->oauthClient->validateOauthAccessToken($authAccessTokenValidationRequestTransfer)->getIsValid();
    }
}
