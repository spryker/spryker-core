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
     * @var array<int, \Spryker\Client\OauthExtension\Dependency\Plugin\AccessTokenValidatorPluginInterface>
     */
    protected array $accessTokenValidatorPlugins;

    /**
     * @param \League\OAuth2\Server\ResourceServer $resourceServer
     * @param array<int, \Spryker\Client\OauthExtension\Dependency\Plugin\AccessTokenValidatorPluginInterface> $accessTokenValidatorPlugins
     */
    public function __construct(
        ResourceServer $resourceServer,
        array $accessTokenValidatorPlugins
    ) {
        $this->resourceServer = $resourceServer;
        $this->accessTokenValidatorPlugins = $accessTokenValidatorPlugins;
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

        $oauthAccessTokenValidationResponseTransfer = $this->executeAccessTokenValidatorPlugins(
            $authAccessTokenValidationRequestTransfer,
            $oauthAccessTokenValidationResponseTransfer,
        );

        if ($oauthAccessTokenValidationResponseTransfer->getError() !== null) {
            return $oauthAccessTokenValidationResponseTransfer;
        }

        try {
            $accessTokenRequest = new ServerRequest(
                'POST',
                '',
                [
                    'Authorization' => $authAccessTokenValidationRequestTransfer->getType() . ' ' . $authAccessTokenValidationRequestTransfer->getAccessToken(),
                ],
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

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    protected function executeAccessTokenValidatorPlugins(
        OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer,
        OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        foreach ($this->accessTokenValidatorPlugins as $accessTokenValidatorPlugin) {
            $oauthAccessTokenValidationResponseTransfer = $accessTokenValidatorPlugin->validate(
                $authAccessTokenValidationRequestTransfer,
                $oauthAccessTokenValidationResponseTransfer,
            );

            if ($oauthAccessTokenValidationResponseTransfer->getError() !== null) {
                return $oauthAccessTokenValidationResponseTransfer;
            }
        }

        return $oauthAccessTokenValidationResponseTransfer;
    }
}
