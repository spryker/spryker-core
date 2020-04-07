<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Persistence;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshToken;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\OauthRevoke\Persistence\OauthRevokePersistenceFactory getFactory()
 */
class OauthRevokeEntityManager extends AbstractEntityManager implements OauthRevokeEntityManagerInterface
{
    protected const COLUMN_REVOKED_AT = 'RevokedAt';

    /**
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return int
     */
    public function deleteExpiredRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): int
    {
        $oauthTokenCriteriaFilterTransfer->requireExpiresAt();

        return $this->getFactory()
            ->createRefreshTokenQuery()
            ->filterByExpiresAt($oauthTokenCriteriaFilterTransfer->getExpiresAt(), Criteria::LESS_EQUAL)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    public function revokeRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void
    {
        $oauthRefreshTokenTransfer->requireIdentifier();
        $this->getFactory()
            ->createRefreshTokenQuery()
            ->filterByIdentifier($oauthRefreshTokenTransfer->getIdentifier())
            ->update([static::COLUMN_REVOKED_AT => (new DateTime())->format('Y-m-d H:i:s')]);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
     *
     * @return void
     */
    public function revokeAllRefreshTokens(ArrayObject $oauthRefreshTokenTransfers): void
    {
        $this->getFactory()
            ->createRefreshTokenQuery()
            ->filterByIdentifier_In($this->getIdentifiersFromTransfers($oauthRefreshTokenTransfers))
            ->update([static::COLUMN_REVOKED_AT => (new DateTime())->format('Y-m-d H:i:s')]);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer
     */
    public function saveRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): OauthRefreshTokenTransfer
    {
        $oauthRefreshTokenMapper = $this->getFactory()->createOauthRefreshTokenMapper();
        $oauthRefreshTokenEntity = $oauthRefreshTokenMapper->mapOauthRefreshTokenTransferToOauthRefreshTokenEntity(
            $oauthRefreshTokenTransfer,
            new SpyOauthRefreshToken()
        );

        $oauthRefreshTokenEntity->save();

        return $oauthRefreshTokenMapper->mapOauthRefreshTokenEntityToOauthRefreshTokenTransfer($oauthRefreshTokenEntity, $oauthRefreshTokenTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
     *
     * @return string[]
     */
    protected function getIdentifiersFromTransfers(ArrayObject $oauthRefreshTokenTransfers): array
    {
        $identifiers = [];
        foreach ($oauthRefreshTokenTransfers as $oauthRefreshTokenTransfer) {
            $oauthRefreshTokenTransfer->requireIdentifier();
            $identifiers[] = $oauthRefreshTokenTransfer->getIdentifier();
        }

        return $identifiers;
    }
}
