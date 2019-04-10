<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use League\OAuth2\Server\Grant\GrantTypeInterface as LeagueGrantTypeInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

interface GrantTypeInterface extends LeagueGrantTypeInterface
{
    /**
     * @param \League\OAuth2\Server\Repositories\UserRepositoryInterface $userRepository
     *
     * @return void
     */
    public function setUserRepository(UserRepositoryInterface $userRepository);

    /**
     * @param \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface $refreshTokenRepository
     *
     * @return void
     */
    public function setRefreshTokenRepository(RefreshTokenRepositoryInterface $refreshTokenRepository);
}
