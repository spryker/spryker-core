<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\Validator;

use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class BearerTokenAuthorizationValidator implements BearerTokenAuthorizationValidatorInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \League\OAuth2\Server\CryptKey[] $publicKeys
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function validateAuthorization(
        ServerRequestInterface $request,
        array $publicKeys,
        AccessTokenRepositoryInterface $accessTokenRepository
    ): ServerRequestInterface {
        $bearerTokenValidator = $this->createBearerTokenValidator($accessTokenRepository);
        $verifiedRequest = null;

        foreach ($publicKeys as $publicKey) {
            try {
                if ($bearerTokenValidator instanceof BearerTokenValidator) {
                    $bearerTokenValidator->setPublicKey($publicKey);
                }
                $verifiedRequest = $bearerTokenValidator->validateAuthorization($request);
            } catch (OAuthServerException $OAuthServerException) {
                continue;
            }
        }

        if (!$verifiedRequest) {
            throw OAuthServerException::accessDenied('Access token could not be verified');
        }

        return $verifiedRequest;
    }

    /**
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     *
     * @return \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface
     */
    protected function createBearerTokenValidator(AccessTokenRepositoryInterface $accessTokenRepository): AuthorizationValidatorInterface
    {
        return new BearerTokenValidator($accessTokenRepository);
    }
}
