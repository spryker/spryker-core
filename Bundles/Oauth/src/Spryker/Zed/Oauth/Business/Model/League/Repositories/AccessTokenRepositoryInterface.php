<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use Generated\Shared\Transfer\OauthAccessTokenTransfer;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface as LeagueAccessTokenRepositoryInterface;

interface AccessTokenRepositoryInterface extends LeagueAccessTokenRepositoryInterface
{
    /**
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param array<\League\OAuth2\Server\Entities\ScopeEntityInterface> $scopes
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenTransfer|null
     */
    public function findAccessToken(ClientEntityInterface $client, array $scopes = []): ?OauthAccessTokenTransfer;
}
