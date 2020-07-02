<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthErrorTransfer;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Symfony\Component\HttpFoundation\Request;

class OauthAccessTokenValidator implements AccessTokenValidatorInterface
{
    protected const HEADER_AUTHORIZATION = 'Authorization';

    /**
     * @var \League\OAuth2\Server\ResourceServer
     */
    protected $resourceServer;

    /**
     * @param \League\OAuth2\Server\ResourceServer $resourceServer
     */
    public function __construct(ResourceServer $resourceServer)
    {
        $this->resourceServer = $resourceServer;
    }

    /**
     * Validates access token without accessing databases. Uses JWT token. Response will contain subject identifier (customer reference)
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validate(OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer): OauthAccessTokenValidationResponseTransfer
    {
        $oauthAccessTokenValidationResponseTransfer = (new OauthAccessTokenValidationResponseTransfer())->setIsValid(false);

        $type = $authAccessTokenValidationRequestTransfer->getType();
        $accessToken = $authAccessTokenValidationRequestTransfer->getAccessToken();

        try {
            $accessTokenRequest = new ServerRequest(
                Request::METHOD_POST,
                '',
                [
                    static::HEADER_AUTHORIZATION => sprintf('%s %s', $type, $accessToken),
                ]
            );

            $response = $this->resourceServer->validateAuthenticatedRequest($accessTokenRequest);

            $oauthAccessTokenValidationResponseTransfer
                ->fromArray($response->getAttributes(), true)
                ->setIsValid(true);
        } catch (OAuthServerException $exception) {
            $oauthErrorTransfer = new OauthErrorTransfer();
            $oauthErrorTransfer
                ->setErrorType($exception->getErrorType())
                ->setMessage($exception->getMessage());

            $oauthAccessTokenValidationResponseTransfer->setError($oauthErrorTransfer);
        }

        return $oauthAccessTokenValidationResponseTransfer;
    }
}
