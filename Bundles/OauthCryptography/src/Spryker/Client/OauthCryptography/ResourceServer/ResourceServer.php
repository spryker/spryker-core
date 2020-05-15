<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\ResourceServer;

use League\OAuth2\Server\ResourceServer as LeagueResourceServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Client\OauthCryptographyExtension\Dependency\Plugin\KeyLoaderInterface;

class ResourceServer extends LeagueResourceServer
{
    /**
     * @var \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface[]
     */
    protected $authorizationValidators;

    /**
     * @var \Spryker\Client\OauthCryptographyExtension\Dependency\Plugin\KeyLoaderInterface
     */
    protected $keyLoader;

    /**
     * @param \Spryker\Client\OauthCryptographyExtension\Dependency\Plugin\KeyLoaderInterface $keyLoader
     * @param \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface[] $authorizationValidators
     */
    public function __construct(
        KeyLoaderInterface $keyLoader,
        array $authorizationValidators
    ) {
        $this->keyLoader = $keyLoader;
        $this->authorizationValidators = $authorizationValidators;
    }

    /**
     * Determine the access token validity.
     *
     * @param ServerRequestInterface $request
     *
     * @throws OAuthServerException
     *
     * @return ServerRequestInterface
     */
    public function validateAuthenticatedRequest(ServerRequestInterface $request)
    {
        $publicKeys = $this->keyLoader->loadKeys();

        foreach ($this->authorizationValidators as $authorizationValidator) {
            try {
                foreach ($publicKeys as $publicKey) {
                    if (method_exists($authorizationValidator, 'setPublicKey')) {
                        $authorizationValidator->setPublicKey($publicKey);
                    }
                    return $authorizationValidator->validateAuthorization($request);
                }
            }
            catch (OAuthServerException $authServerException) {
                continue;
            }
        }

        // There is no validator to grant access.
        throw OAuthServerException::accessDenied('No validator found to authorize the token.');
    }
}
