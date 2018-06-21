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

class AccessTokenValidator implements AccessTokenValidatorInterface
{
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
        $oauthAcessTokenValidationResponseTransfer = (new OauthAccessTokenValidationResponseTransfer())->setIsValid(false);

        try {
            $accessTokenRequest = new ServerRequest(
                'POST',
                '',
                [
                    'Authorization' => $authAccessTokenValidationRequestTransfer->getType() . ' ' . $authAccessTokenValidationRequestTransfer->getAccessToken(),
                ]
            );

            $response = $this->resourceServer->validateAuthenticatedRequest($accessTokenRequest);

            $oauthAcessTokenValidationResponseTransfer
                ->fromArray($response->getAttributes(), true)
                ->setIsValid(true);
        } catch (OAuthServerException $exception) {
            $oauthErrorTransfer = new OauthErrorTransfer();
            $oauthErrorTransfer
                ->setErrorType($exception->getErrorType())
                ->setMessage($exception->getMessage());

            $oauthAcessTokenValidationResponseTransfer->setError($oauthErrorTransfer);
        }

        return $oauthAcessTokenValidationResponseTransfer;
    }
}
