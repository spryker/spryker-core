<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OauthAccessTokenValidator implements OauthAccessTokenValidatorInterface
{
    protected const REQUEST_ATTRIBUTE_IS_PROTECTED = 'is-protected';
    protected const AUTHORIZATION_HEADER_TYPE = 0;
    protected const AUTHORIZATION_HEADER_CREDENTIALS = 1;

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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $request, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $isProtected = $request->attributes->get(static::REQUEST_ATTRIBUTE_IS_PROTECTED, false);

        $authorizationToken = $request->headers->get(AuthRestApiConfig::HEADER_AUTHORIZATION);
        if (!$authorizationToken && $isProtected === true) {
            return (new RestErrorCollectionTransfer())->addRestError(
                $this->createErrorMessageTransfer(
                    AuthRestApiConfig::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN,
                    Response::HTTP_FORBIDDEN,
                    AuthRestApiConfig::RESPONSE_CODE_FORBIDDEN
                )
            );
        }

        if (!$authorizationToken) {
            return null;
        }

        if (!$this->validateAccessToken($authorizationToken)) {
            return (new RestErrorCollectionTransfer())->addRestError(
                $this->createErrorMessageTransfer(
                    AuthRestApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN,
                    Response::HTTP_UNAUTHORIZED,
                    AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID
                )
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
    protected function createErrorMessageTransfer(string $detail, int $status, string $code): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setStatus($status)
            ->setCode($code);
    }

    /**
     * @param string $authorizationToken
     *
     * @return array
     */
    protected function extractToken(string $authorizationToken): array
    {
        return preg_split('/\s+/', $authorizationToken);
    }

    /**
     * @param string $authorizationToken
     *
     * @return bool
     */
    protected function validateAccessToken(string $authorizationToken): bool
    {
        $authorizationTokenItems = $this->extractToken($authorizationToken);
        if (!isset($authorizationTokenItems[static::AUTHORIZATION_HEADER_CREDENTIALS])) {
            return false;
        }

        $authAccessTokenValidationRequestTransfer = new OauthAccessTokenValidationRequestTransfer();
        $authAccessTokenValidationRequestTransfer
            ->setAccessToken($authorizationTokenItems[static::AUTHORIZATION_HEADER_CREDENTIALS])
            ->setType($authorizationTokenItems[static::AUTHORIZATION_HEADER_TYPE]);

        $authAccessTokenValidationResponseTransfer = $this->oauthClient->validateAccessToken(
            $authAccessTokenValidationRequestTransfer
        );

        return $authAccessTokenValidationResponseTransfer->getIsValid();
    }
}
