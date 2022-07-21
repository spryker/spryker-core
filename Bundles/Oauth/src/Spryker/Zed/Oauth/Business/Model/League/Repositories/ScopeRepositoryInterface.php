<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface as LeagueScopeRepositoryInterface;

interface ScopeRepositoryInterface extends LeagueScopeRepositoryInterface
{
    /**
     * @param string $identifier The scope identifier
     * @param string|null $applicationName
     *
     * @return \League\OAuth2\Server\Entities\ScopeEntityInterface|null
     */
    public function getScopeEntityByIdentifier($identifier, ?string $applicationName = null);

    /**
     * @param array<\League\OAuth2\Server\Entities\ScopeEntityInterface> $scopes
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @param string|null $userIdentifier
     * @param string|null $applicationName
     *
     * @return array<\League\OAuth2\Server\Entities\ScopeEntityInterface>
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null,
        ?string $applicationName = null
    );
}
