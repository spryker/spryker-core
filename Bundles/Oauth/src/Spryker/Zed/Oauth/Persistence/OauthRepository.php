<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use Generated\Shared\Transfer\OauthAccessTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;
use Orm\Zed\Oauth\Persistence\SpyOauthAccessTokenQuery;
use Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Oauth\Persistence\OauthPersistenceFactory getFactory()
 */
class OauthRepository extends AbstractRepository implements OauthRepositoryInterface
{
    public const CUSTOMER_REFERENCE_PATTERN = '%%"customer_reference":"%s"%%';

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
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findRefreshToken(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer
    {
        $oauthRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $oauthRefreshTokenQuery = $this->applyRefreshTokenFilters($oauthRefreshTokenQuery, $oauthTokenCriteriaFilterTransfer);

        $oauthRefreshTokenEntity = $oauthRefreshTokenQuery->findOne();

        if (!$oauthRefreshTokenEntity) {
            return null;
        }

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityToOauthRefreshTokenTransfer($oauthRefreshTokenEntity, new OauthRefreshTokenTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer
     */
    public function findRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): OauthRefreshTokenCollectionTransfer
    {
        $oauthRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $oauthRefreshTokenQuery = $this->applyRefreshTokenFilters($oauthRefreshTokenQuery, $oauthTokenCriteriaFilterTransfer);

        $oauthRefreshTokensCollection = $oauthRefreshTokenQuery->find();

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityCollectionToOauthRefreshTokenTransferCollection($oauthRefreshTokensCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenCollectionTransfer
     */
    public function findAccessTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): OauthAccessTokenCollectionTransfer
    {
        $oauthAccessTokenQuery = $this->getFactory()->createAccessTokenQuery();
        $oauthAccessTokenQuery = $this->applyAccessTokenFilters($oauthAccessTokenQuery, $oauthTokenCriteriaFilterTransfer);

        $oauthAccessTokensCollection = $oauthAccessTokenQuery->find();

        return $this->getFactory()
            ->createOauthAccessTokenMapper()
            ->mapOauthAccessTokenEntityCollectionToOauthAccessTokenTransferCollection($oauthAccessTokensCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return bool
     */
    public function isRefreshTokenRevoked(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): bool
    {
        $oauthRefreshTokenTransfer = $this->findRefreshToken(
            (new OauthTokenCriteriaFilterTransfer())->setIdentifier($oauthRefreshTokenTransfer->getIdentifier())
        );

        return !empty($oauthRefreshTokenTransfer->getRevokedAt());
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
     * @param \Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery $oauthRefreshTokenQuery
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyRefreshTokenFilters(
        SpyOauthRefreshTokenQuery $oauthRefreshTokenQuery,
        OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
    ): ModelCriteria {
        if ($oauthTokenCriteriaFilterTransfer->getCustomerReference()) {
            $oauthRefreshTokenQuery->filterByUserIdentifier_Like(
                sprintf(static::CUSTOMER_REFERENCE_PATTERN, $oauthTokenCriteriaFilterTransfer->getCustomerReference())
            );
        }

        if ($oauthTokenCriteriaFilterTransfer->getIdentifier()) {
            $oauthRefreshTokenQuery->filterByIdentifier($oauthTokenCriteriaFilterTransfer->getIdentifier());
        }

        if ($oauthTokenCriteriaFilterTransfer->getRevokedAt()) {
            $oauthRefreshTokenQuery->filterByRevokedAt($oauthTokenCriteriaFilterTransfer->getRevokedAt(), Criteria::ISNULL);
        }

        return $oauthRefreshTokenQuery;
    }

    /**
     * @param \Orm\Zed\Oauth\Persistence\SpyOauthAccessTokenQuery $oauthAccessTokenQuery
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyAccessTokenFilters(
        SpyOauthAccessTokenQuery $oauthAccessTokenQuery,
        OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
    ): ModelCriteria {
        if ($oauthTokenCriteriaFilterTransfer->getCustomerReference()) {
            $oauthAccessTokenQuery->filterByUserIdentifier_Like(
                sprintf(static::CUSTOMER_REFERENCE_PATTERN, $oauthTokenCriteriaFilterTransfer->getCustomerReference())
            );
        }

        if ($oauthTokenCriteriaFilterTransfer->getIdentifier()) {
            $oauthAccessTokenQuery->filterByIdentifier($oauthTokenCriteriaFilterTransfer->getIdentifier());
        }

        return $oauthAccessTokenQuery;
    }
}
