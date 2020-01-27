<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\RefreshTokenCriteriaFilterTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;
use Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Oauth\Persistence\OauthPersistenceFactory getFactory()
 */
class OauthRepository extends AbstractRepository implements OauthRepositoryInterface
{
    public const CUSTOMER_REFERENCE_PATTERN = '%"customer_reference":"';

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
     * @param string[] $customerScopes
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
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
                true
            );
        }

        return $oauthScopeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findRefreshToken(RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer
    {
        $authRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $authRefreshTokenQuery = $this->applyFilters($authRefreshTokenQuery, $refreshTokenCriteriaFilterTransfer);

        $authRefreshTokenEntity = $authRefreshTokenQuery->findOne();

        if (!$authRefreshTokenEntity) {
            return null;
        }

        return $authRefreshTokenTransfer = $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityToOauthRefreshTokenTransfer($authRefreshTokenEntity, new OauthRefreshTokenTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer
     */
    public function findRefreshTokens(RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer): OauthRefreshTokenCollectionTransfer
    {
        $authRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $authRefreshTokenQuery = $this->applyFilters($authRefreshTokenQuery, $refreshTokenCriteriaFilterTransfer);

        $authRefreshTokensCollection = $authRefreshTokenQuery->find();

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityCollectionToOauthRefreshTokenTransferCollection($authRefreshTokensCollection);
    }

    /**
     * @param string $expiredAt
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer
     */
    public function getExpiredRefreshTokens(string $expiredAt): OauthRefreshTokenCollectionTransfer
    {
        $authRefreshTokensCollection = $this->getFactory()
            ->createRefreshTokenQuery()
            ->filterByExpiresAt($expiredAt, Criteria::LESS_EQUAL)
            ->find();

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityCollectionToOauthRefreshTokenTransferCollection($authRefreshTokensCollection);
    }

    /**
     * @param \Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery $authRefreshTokenQuery
     * @param \Generated\Shared\Transfer\RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery
     */
    protected function applyFilters(
        SpyOauthRefreshTokenQuery $authRefreshTokenQuery,
        RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer
    ): SpyOauthRefreshTokenQuery {
        if ($refreshTokenCriteriaFilterTransfer->getCustomerReference()) {
            $authRefreshTokenQuery->filterByUserIdentifier_Like(
                static::CUSTOMER_REFERENCE_PATTERN . $refreshTokenCriteriaFilterTransfer->getCustomerReference() . '"%'
            );
        }

        if ($refreshTokenCriteriaFilterTransfer->getIdentifier()) {
            $authRefreshTokenQuery->filterByIdentifier($refreshTokenCriteriaFilterTransfer->getIdentifier());
        }

        if ($refreshTokenCriteriaFilterTransfer->getRevokedAt()) {
            $authRefreshTokenQuery->filterByRevokedAt($refreshTokenCriteriaFilterTransfer->getRevokedAt());
        }

        return $authRefreshTokenQuery;
    }
}
