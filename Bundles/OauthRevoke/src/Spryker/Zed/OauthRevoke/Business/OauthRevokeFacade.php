<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business;

use ArrayObject;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeBusinessFactory getFactory()
 * @method \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeRepositoryInterface getRepository()
 * @method \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface getEntityManager()
 */
class OauthRevokeFacade extends AbstractFacade implements OauthRevokeFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findOne(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer
    {
        return $this->getRepository()->findOne($oauthTokenCriteriaFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    public function revokeRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void
    {
        if ($this->isRefreshTokenRevoked($oauthRefreshTokenTransfer)) {
            return;
        }
        $this->getEntityManager()->revokeRefreshToken($oauthRefreshTokenTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function revokeRefreshTokens(ArrayObject $oauthRefreshTokenTransfers)
    {
        // TODO: Implement revokeRefreshTokens() method.
    }

    public function isRefreshTokenRevoked(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer)
    {
        return $this->getRepository()->isRefreshTokenRevoked($oauthRefreshTokenTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $this->getFactory()->createOauthRefreshTokenCreator()->saveRefreshToken($refreshTokenEntity);
    }

//    protected function getCustomerReference(?string $userIdentifier): ?string
//    {
////        $encodedUserIdentifier = $this->utilEncodingService
////            ->decodeJson($userIdentifier);
//
//        $encodedUserIdentifier = json_decode($userIdentifier);
//
//        return $encodedUserIdentifier->customer_reference ?? null;
//    }
}
