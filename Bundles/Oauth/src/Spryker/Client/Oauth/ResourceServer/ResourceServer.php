<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\ResourceServer as LeagueResourceServer;
use Psr\Http\Message\ServerRequestInterface;

class ResourceServer extends LeagueResourceServer
{
    /**
     * @var \Spryker\Client\OauthExtension\Dependency\Plugin\AuthorizationValidatorPluginInterface[]
     */
    protected $authorizationValidators;

    /**
     * @var \League\OAuth2\Server\CryptKey[]
     */
    protected $publicKeys;

    /**
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
     * @param \League\OAuth2\Server\CryptKey[] $publicKeys
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     * @param \Spryker\Client\OauthExtension\Dependency\Plugin\AuthorizationValidatorPluginInterface[] $authorizationValidatorPlugins
     */
    public function __construct(
        array $publicKeys,
        AccessTokenRepositoryInterface $accessTokenRepository,
        array $authorizationValidatorPlugins
    ) {
        $this->publicKeys = $publicKeys;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->authorizationValidators = $authorizationValidatorPlugins;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function validateAuthenticatedRequest(ServerRequestInterface $request)
    {
        $verifiedRequest = null;
        foreach ($this->authorizationValidators as $authorizationValidator) {
            try {
                $verifiedRequest = $authorizationValidator->validateAuthorization(
                    $request,
                    $this->publicKeys,
                    $this->accessTokenRepository
                );
            } catch (OAuthServerException $authServerException) {
                continue;
            }
        }

        if (!$verifiedRequest) {
            throw OAuthServerException::accessDenied('No validator found to authorize the token.');
        }

        return $verifiedRequest;
    }
}
