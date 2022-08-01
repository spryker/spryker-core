<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use Generated\Shared\Transfer\OauthAccessTokenTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * @method \Spryker\Zed\Oauth\Persistence\OauthPersistenceFactory getFactory()
 */
interface OauthRepositoryInterface
{
    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\SpyOauthClientEntityTransfer|null
     */
    public function findClientByIdentifier(string $identifier): ?SpyOauthClientEntityTransfer;

    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\SpyOauthScopeEntityTransfer|null
     */
    public function findScopeByIdentifier(string $identifier): ?SpyOauthScopeEntityTransfer;

    /**
     * @param array<string> $customerScopes
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopesByIdentifiers(array $customerScopes): array;

    /**
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param array<\League\OAuth2\Server\Entities\ScopeEntityInterface> $scopes
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenTransfer|null
     */
    public function findAccessToken(ClientEntityInterface $client, array $scopes = []): ?OauthAccessTokenTransfer;
}
