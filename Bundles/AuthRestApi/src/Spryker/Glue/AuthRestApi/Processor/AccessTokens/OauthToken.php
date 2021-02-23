<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Spryker\Client\AuthRestApi\AuthRestApiClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class OauthToken implements OauthTokenInterface
{
    /**
     * @var \Spryker\Client\AuthRestApi\AuthRestApiClientInterface
     */
    protected $authRestApiClient;

    /**
     * @param \Spryker\Client\AuthRestApi\AuthRestApiClientInterface $authRestApiClient
     */
    public function __construct(AuthRestApiClientInterface $authRestApiClient)
    {
        $this->authRestApiClient = $authRestApiClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAccessToken(OauthRequestTransfer $oauthRequestTransfer): JsonResponse
    {
        $oauthResponseTransfer = $this->authRestApiClient->createAccessToken($oauthRequestTransfer);
        $response = new JsonResponse();
        if (!$oauthResponseTransfer->getIsValid()) {
            /**
             * @see https://tools.ietf.org/html/rfc6749#section-5.2
             */
            $response->setStatusCode(400);

            //This is added for BC reasons since Oauth module is not compliant with above RFC, this shim is needed
            //to make the API endpoint compliant until a major change updates Oauth
            if ($oauthResponseTransfer->getError()->getErrorType() === 'invalid_credentials') {
                return $response->setData([
                    'error' => 'invalid_grant',
                    'error_description' => 'The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token '
                        . 'is invalid, expired, revoked, does not match the redirection URI used in the authorization request, '
                        . 'or was issued to another client.',
                ]);
            }

            return $response->setData([
                'error' => $oauthResponseTransfer->getError()->getErrorType(),
                'error_description' => $oauthResponseTransfer->getError()->getMessage(),
            ]);
        }

        /**
         * @see https://tools.ietf.org/html/rfc6749#section-5.1
         */
        return $response->setData([
            'access_token' => $oauthResponseTransfer->getAccessToken(),
            'token_type' => $oauthResponseTransfer->getTokenType(),
            'expires_in' => $oauthResponseTransfer->getExpiresIn(),
            'refresh_token' => $oauthResponseTransfer->getRefreshToken(),
        ])->setStatusCode(200);
    }
}
