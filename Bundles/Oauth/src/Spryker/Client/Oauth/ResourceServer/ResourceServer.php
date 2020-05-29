<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface as AccessTokenRepositoryInterfaceAlias;
use League\OAuth2\Server\ResourceServer as LeagueResourceServer;
use Psr\Http\Message\ServerRequestInterface;

class ResourceServer extends LeagueResourceServer
{
    /**
     * @var \Spryker\Client\OauthExtention\Dependency\Plugin\AuthorizationValidatorPluginInterface[]
     */
    protected $authorizationValidators;

    /**
     * @var \League\OAuth2\Server\CryptKey[]
     */
    private $publicKeys;

    /**
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
     * @param \League\OAuth2\Server\CryptKey[] $publicKeys
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     * @param \Spryker\Client\OauthExtention\Dependency\Plugin\AuthorizationValidatorPluginInterface[] $authorizationValidators
     */
    public function __construct(
        array $publicKeys,
        AccessTokenRepositoryInterfaceAlias $accessTokenRepository,
        array $authorizationValidators
    ) {
        $this->publicKeys = $publicKeys;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->authorizationValidators = $authorizationValidators;
    }

    /**
     * Determine the access token validity.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function validateAuthenticatedRequest(ServerRequestInterface $request)
    {
        foreach ($this->authorizationValidators as $authorizationValidator) {
            try {
                $authorizationValidator->setPublicKeys($this->publicKeys);
                $authorizationValidator->setRepository($this->accessTokenRepository);

                return $authorizationValidator->validateAuthorization($request);
            }
            catch (OAuthServerException $authServerException) {
                continue;
            }
        }

        // There is no validator to grant access.
        throw OAuthServerException::accessDenied('No validator found to authorize the token.');
    }
}
