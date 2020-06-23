<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\Communication\Plugin\Oauth;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\OauthExtension\Dependency\Plugin\AuthorizationValidatorPluginInterface;

/**
 * @method \Spryker\Client\OauthCryptography\OauthCryptographyClientInterface getClient()
 * @method \Spryker\Client\OauthCryptography\OauthCryptographyConfig getConfig()
 */
class BearerTokenAuthorizationValidatorPlugin extends AbstractPlugin implements AuthorizationValidatorPluginInterface
{
    /**
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
     * @var \League\OAuth2\Server\CryptKey[]
     */
    protected $publicKeys;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $repository
     *
     * @return void
     */
    public function setRepository(AccessTokenRepositoryInterface $repository): void
    {
        $this->accessTokenRepository = $repository;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \League\OAuth2\Server\CryptKey[] $publicKeys
     *
     * @return void
     */
    public function setPublicKeys(array $publicKeys): void
    {
        $this->publicKeys = $publicKeys;
    }

    /**
     * {@inheritDoc}
     * - Checks if `authorization` header is present.
     * - Parses the JWT token.
     * - Verifies the token against each public key.
     * - Checks access token repository for the verified token to have been revoked.
     * - Adds oauth additional attributes to the request on success.
     * - Throws `OAuthServerException` if any of the steps fails.
     *
     * @api
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function validateAuthorization(ServerRequestInterface $request)
    {
        return $this->getClient()->validateAuthorization(
            $request,
            $this->publicKeys,
            $this->accessTokenRepository
        );
    }
}
