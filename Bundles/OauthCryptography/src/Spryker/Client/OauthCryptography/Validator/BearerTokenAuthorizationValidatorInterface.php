<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\Validator;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

interface BearerTokenAuthorizationValidatorInterface
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
    ): ServerRequestInterface;
}
