<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\UserRepositoryInterface;

interface RepositoryBuilderInterface
{
    /**
     * @return \League\OAuth2\Server\Repositories\ClientRepositoryInterface
     */
    public function createClientRepository(): ClientRepositoryInterface;

    /**
     * @return \League\OAuth2\Server\Repositories\ScopeRepositoryInterface
     */
    public function createScopeRepository(): ScopeRepositoryInterface;

    /**
     * @return \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    public function createAccessTokenRepository(): AccessTokenRepositoryInterface;

    /**
     * @return \Spryker\Zed\Oauth\Business\Model\League\Repositories\UserRepositoryInterface
     */
    public function createUserRepository(): UserRepositoryInterface;

    /**
     * @return \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface
     */
    public function createRefreshTokenRepository(): RefreshTokenRepositoryInterface;
}
