<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthExtention\Dependency\Plugin;

use \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface as LeagueAuthorizationValidatorInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

interface AuthorizationValidatorPluginInterface extends LeagueAuthorizationValidatorInterface
{
    /**
     * @param \League\OAuth2\Server\CryptKey[] $keys
     *
     * @return void
     */
    public function setPublicKeys(array $keys): void;

    /**
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $repository
     *
     * @return void
     */
    public function setRepository(AccessTokenRepositoryInterface $repository): void;
}
