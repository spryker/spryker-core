<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use DateTimeImmutable;
use Generated\Shared\Transfer\OauthAccessTokenTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Oauth\Persistence\OauthPersistenceFactory getFactory()
 */
class OauthRepository extends AbstractRepository implements OauthRepositoryInterface
{
    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\SpyOauthClientEntityTransfer|null
     */
    public function findClientByIdentifier(string $identifier): ?SpyOauthClientEntityTransfer
    {
        $query = $this->getFactory()
            ->createOauthClientQuery()
            ->filterByIdentifier($identifier);

        return $this->buildQueryFromCriteria($query)->findOne();
    }

    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\SpyOauthScopeEntityTransfer|null
     */
    public function findScopeByIdentifier(string $identifier): ?SpyOauthScopeEntityTransfer
    {
        $query = $this->getFactory()
            ->createScopeQuery()
            ->filterByIdentifier($identifier);

        return $this->buildQueryFromCriteria($query)->findOne();
    }

    /**
     * @param array<string> $customerScopes
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopesByIdentifiers(array $customerScopes): array
    {
        $spyOauthScopeEntities = $this->getFactory()
            ->createScopeQuery()
            ->filterByIdentifier_In($customerScopes)
            ->find();

        $oauthScopeTransfers = [];

        if ($spyOauthScopeEntities->count() === 0) {
            return $oauthScopeTransfers;
        }

        foreach ($spyOauthScopeEntities as $spyOauthScopeEntity) {
            $oauthScopeTransfer = new OauthScopeTransfer();
            $oauthScopeTransfers[] = $oauthScopeTransfer->fromArray(
                $spyOauthScopeEntity->toArray(),
                true,
            );
        }

        return $oauthScopeTransfers;
    }

    /**
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param array<\League\OAuth2\Server\Entities\ScopeEntityInterface> $scopes
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenTransfer|null
     */
    public function findAccessToken(ClientEntityInterface $client, array $scopes = []): ?OauthAccessTokenTransfer
    {
        $scopeIdentifiers = [];
        foreach ($scopes as $scope) {
            $scopeIdentifiers[] = $scope->getIdentifier();
        }
        $scopes = sprintf('["%s"]', implode('", "', $scopeIdentifiers));

        $oauthAccessTokenEntity = $this->getFactory()
            ->createAccessTokenQuery()
            ->filterByFkOauthClient($client->getIdentifier())
            ->filterByScopes($scopes)
            ->filterByExpirityDate(['min' => new DateTimeImmutable('now')], Criteria::GREATER_EQUAL)
            ->orderByIdOauthAccessToken(Criteria::DESC)
            ->findOne();

        if ($oauthAccessTokenEntity === null) {
            return null;
        }

        return $this->getFactory()->createOauthTokenMapper()->mapOauthAccessTokenEntityToOauthAccessTokenTransfer($oauthAccessTokenEntity, new OauthAccessTokenTransfer());
    }
}
