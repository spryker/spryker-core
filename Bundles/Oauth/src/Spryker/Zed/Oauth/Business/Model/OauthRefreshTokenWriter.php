<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use DateTime;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class OauthRefreshTokenWriter implements OauthRefreshTokenWriterInterface
{
    use TransactionTrait;

    protected const KEY_ACCESS_TOKEN_ID = 'access_token_id';

    protected const ERROR_INVALID_REFRESH_TOKEN = 'Refresh token "%s" is not found';
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface
     */
    protected $oauthEntityManager;

    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     */
    public function __construct(OauthRepositoryInterface $oauthRepository, OauthEntityManagerInterface $oauthEntityManager)
    {
        $this->oauthRepository = $oauthRepository;
        $this->oauthEntityManager = $oauthEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeConcreteRefreshToken(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenResponseTransfer = (new RevokeRefreshTokenResponseTransfer())->setIsSuccessful(false);

        $revokeRefreshTokenRequestTransfer->requireRefreshToken();

        $oauthRefreshTokenTransfer = $this->oauthRepository->findRefreshTokenByIdentifier($revokeRefreshTokenRequestTransfer->getRefreshToken());

        if (!$oauthRefreshTokenTransfer) {
            $revokeRefreshTokenResponseTransfer->setError(
                sprintf(static::ERROR_INVALID_REFRESH_TOKEN, $revokeRefreshTokenRequestTransfer->getRefreshToken())
            );

            return $revokeRefreshTokenResponseTransfer;
        }

        if ($oauthRefreshTokenTransfer->getRevokedAt()) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(true);
        }

        $oauthRefreshTokenTransfer->setRevokedAt((new DateTime())->format("Y-m-d H:i:s.u"));

        $this->revokeConcreteRefreshTokenWithAccessToken($oauthRefreshTokenTransfer);

        return $revokeRefreshTokenResponseTransfer
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    protected function revokeConcreteRefreshTokenWithAccessToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($oauthRefreshTokenTransfer): void {
            $this->executeRevokeConcreteRefreshTokenWithAccessTokenTransaction($oauthRefreshTokenTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    protected function executeRevokeConcreteRefreshTokenWithAccessTokenTransaction(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void
    {
        $this->oauthEntityManager->revokeRefreshToken($oauthRefreshTokenTransfer);
//        $this->oauthEntityManager->deleteAccessTokenByIdentifier(json_decode($this->decrypt($oauthRefreshTokenTransfer), true)[self::KEY_ACCESS_TOKEN_ID]);
    }

    /**
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshTokensByCustomer(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenResponseTransfer = (new RevokeRefreshTokenResponseTransfer())->setIsSuccessful(false);

        $revokeRefreshTokenRequestTransfer->requireCustomer();

        $oauthRefreshTokenTransfer = $this->oauthRepository->findRefreshTokensByCustomer($revokeRefreshTokenRequestTransfer->getCustomer());

        if (!$oauthRefreshTokenTransfer) {
            $revokeRefreshTokenResponseTransfer->setError(
                sprintf(static::ERROR_INVALID_REFRESH_TOKEN, $revokeRefreshTokenRequestTransfer->getRefreshToken())
            );

            return $revokeRefreshTokenResponseTransfer;
        }

        if ($oauthRefreshTokenTransfer->getRevokedAt()) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(true);
        }

        $oauthRefreshTokenTransfer->setRevokedAt((new DateTime())->format("Y-m-d H:i:s.u"));

        $this->revokeConcreteRefreshTokenWithAccessToken($oauthRefreshTokenTransfer);

        $revokeRefreshTokenResponseTransfer
            ->setIsSuccessful(true);
    }
}
