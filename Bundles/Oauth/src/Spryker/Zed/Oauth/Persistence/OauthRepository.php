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
        $oauthRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $oauthRefreshTokenQuery = $this->applyFilters($oauthRefreshTokenQuery, $refreshTokenCriteriaFilterTransfer);

        $oauthRefreshTokenEntity = $oauthRefreshTokenQuery->findOne();

        if (!$oauthRefreshTokenEntity) {
            return null;
        }

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityToOauthRefreshTokenTransfer($oauthRefreshTokenEntity, new OauthRefreshTokenTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer
     */
    public function findRefreshTokens(RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer): OauthRefreshTokenCollectionTransfer
    {
        $oauthRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $oauthRefreshTokenQuery = $this->applyFilters($oauthRefreshTokenQuery, $refreshTokenCriteriaFilterTransfer);

        $oauthRefreshTokensCollection = $oauthRefreshTokenQuery->find();

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityCollectionToOauthRefreshTokenTransferCollection($oauthRefreshTokensCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return bool
     */
    public function isRefreshTokenRevoked(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): bool
    {
        $oauthRefreshTokenTransfer = $this->findRefreshToken(
            (new RefreshTokenCriteriaFilterTransfer())->setIdentifier($oauthRefreshTokenTransfer->getIdentifier())
        );

        return !empty($oauthRefreshTokenTransfer->getRevokedAt());
    }

    /**
     * @param \Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery $oauthRefreshTokenQuery
     * @param \Generated\Shared\Transfer\RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery
     */
    protected function applyFilters(
        SpyOauthRefreshTokenQuery $oauthRefreshTokenQuery,
        RefreshTokenCriteriaFilterTransfer $refreshTokenCriteriaFilterTransfer
    ): SpyOauthRefreshTokenQuery {
        if ($refreshTokenCriteriaFilterTransfer->getCustomerReference()) {
            $oauthRefreshTokenQuery->filterByUserIdentifier_Like(
                static::CUSTOMER_REFERENCE_PATTERN . $refreshTokenCriteriaFilterTransfer->getCustomerReference() . '"%'
            );
        }

        if ($refreshTokenCriteriaFilterTransfer->getIdentifier()) {
            $oauthRefreshTokenQuery->filterByIdentifier($refreshTokenCriteriaFilterTransfer->getIdentifier());
        }

        if ($refreshTokenCriteriaFilterTransfer->getRevokedAt()) {
            $oauthRefreshTokenQuery->filterByRevokedAt($refreshTokenCriteriaFilterTransfer->getRevokedAt(), Criteria::ISNULL);
        }

        return $oauthRefreshTokenQuery;
    }
}
