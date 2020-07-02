<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

interface OauthCryptographyClientInterface
{
    /**
     * Specification:
     * - Loads the default configured public ssh key.
     * - Creates `CryptKey` instance in case the configured key is not one.
     *
     * @api
     *
     * @return \League\OAuth2\Server\CryptKey[]
     */
    public function loadPublicKeys(): array;

    /**
     * Specification:
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
