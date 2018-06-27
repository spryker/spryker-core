<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

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
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validate(
        OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        $authAccessTokenValidationRequestTransfer
            ->requireAccessToken()
            ->requireType();

        $oauthAccessTokenValidationResponseTransfer = (new OauthAccessTokenValidationResponseTransfer())
            ->setIsValid(false);

        try {
            $request = new ServerRequest(
                'POST',
                '',
                [
                    'Authorization' => $authAccessTokenValidationRequestTransfer->getType() . ' ' . $authAccessTokenValidationRequestTransfer->getAccessToken(),
                ]
            );

            $response = $this->resourceServer->validateAuthenticatedRequest($request);

            $oauthAccessTokenValidationResponseTransfer
                ->fromArray($response->getAttributes(), true)
                ->setIsValid(true);
        } catch (OAuthServerException $exception) {
            $oauthErrorTransfer = (new OauthErrorTransfer())
                ->setErrorType($exception->getErrorType())
                ->setMessage($exception->getMessage());

            $oauthAccessTokenValidationResponseTransfer->setError($oauthErrorTransfer);
        }

        return $oauthAccessTokenValidationResponseTransfer;
    }
}
