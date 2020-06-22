<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthExtention\Dependency\Plugin;

use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface as LeagueAuthorizationValidatorInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * Authorization validator plugin for validating bearer token authorization.
 *
 * Plugin interface assumes multiple encryption keys.
 */
interface AuthorizationValidatorPluginInterface extends LeagueAuthorizationValidatorInterface
{
    /**
     * Specification:
     * - Sets array of public keys to the validator.
     *
     * @api
     *
     * @param \League\OAuth2\Server\CryptKey[] $publicKeys
     *
     * @return void
     */
    public function setPublicKeys(array $publicKeys): void;

    /**
     * Specification:
     * - Sets the access token repository instance to the validator.
     *
     * @api
     *
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $repository
     *
     * @return void
     */
    public function setRepository(AccessTokenRepositoryInterface $repository): void;
}
