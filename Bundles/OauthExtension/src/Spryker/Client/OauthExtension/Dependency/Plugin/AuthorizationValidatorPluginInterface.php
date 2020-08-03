<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthExtension\Dependency\Plugin;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Authorization validator plugin for validating bearer token authorization.
 *
 * Plugin interface assumes multiple encryption keys.
 */
interface AuthorizationValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates request with the keys given as a parameter.
     * - Can use AccessTokenRepositoryInterface to pull data on the token.
     *
     * @api
     *
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
    ): ServerRequestInterface;
}
