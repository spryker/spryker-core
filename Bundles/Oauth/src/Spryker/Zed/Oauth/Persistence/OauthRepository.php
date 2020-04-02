<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

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

//    /**
//     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
//     *
//     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
//     */
//    public function findRefreshToken(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer
//    {
//        $oauthTokenCriteriaFilterTransfer->requireIdentifier();
//
//        $oauthRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
//        $oauthRefreshTokenQuery = $this->applyRefreshTokenFilters($oauthRefreshTokenQuery, $oauthTokenCriteriaFilterTransfer);
//
//        $oauthRefreshTokenEntity = $oauthRefreshTokenQuery->findOne();
//
//        if (!$oauthRefreshTokenEntity) {
//            return null;
//        }
//
//        return $this->getFactory()
//            ->createOauthRefreshTokenMapper()
//            ->mapOauthRefreshTokenEntityToOauthRefreshTokenTransfer($oauthRefreshTokenEntity, new OauthRefreshTokenTransfer());
//    }

    /**
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer
     */
    public function getRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): OauthRefreshTokenCollectionTransfer
    {
        $oauthRefreshTokenQuery = $this->getFactory()->createRefreshTokenQuery();
        $oauthRefreshTokenQuery = $this->applyRefreshTokenFilters($oauthRefreshTokenQuery, $oauthTokenCriteriaFilterTransfer);

        $oauthRefreshTokenCollection = $oauthRefreshTokenQuery->find();

        return $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityCollectionToOauthRefreshTokenTransferCollection($oauthRefreshTokenCollection);
    }

//    /**
//     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
//     *
//     * @return bool
//     */
//    public function isRefreshTokenRevoked(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): bool
//    {
//        return $this->getFactory()->createRefreshTokenQuery()
//            ->filterByIdentifier($oauthRefreshTokenTransfer->getIdentifier())
//            ->filterByRevokedAt(null, Criteria::NOT_EQUAL)
//            ->exists();
//    }

//    /**
//     * @param \Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery $oauthRefreshTokenQuery
//     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
//     *
//     * @return \Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery
//     */
//    protected function applyRefreshTokenFilters(
//        SpyOauthRefreshTokenQuery $oauthRefreshTokenQuery,
//        OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
//    ): SpyOauthRefreshTokenQuery {
//        if ($oauthTokenCriteriaFilterTransfer->getIdentifier()) {
//            $oauthRefreshTokenQuery->filterByIdentifier($oauthTokenCriteriaFilterTransfer->getIdentifier());
//        }
//
//        if ($oauthTokenCriteriaFilterTransfer->getCustomerReference()) {
//            $oauthRefreshTokenQuery->filterByCustomerReference($oauthTokenCriteriaFilterTransfer->getCustomerReference());
//        }
//
//        $oauthRefreshTokenQuery->filterByRevokedAt(
//            null,
//            $oauthTokenCriteriaFilterTransfer->getIsRevoked() ? Criteria::ISNOTNULL : Criteria::ISNULL
//        );
//
//        return $oauthRefreshTokenQuery;
//    }
}
