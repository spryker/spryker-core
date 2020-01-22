<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\RefreshTokenCriteriaFilterTransfer;
use Generated\Shared\Transfer\RefreshTokenErrorTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class OauthRefreshTokenWriter implements OauthRefreshTokenWriterInterface
{
    use TransactionTrait;

    protected const KEY_ACCESS_TOKEN_ID = 'access_token_id';

    protected const REFRESH_TOKEN_INVALID_ERROR_MESSAGE = 'Refresh token "%s" is not found';
    protected const REFRESH_TOKEN_INVALID_ERROR_TYPE = 100;

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
        $revokeRefreshTokenResponseTransfer = (new RevokeRefreshTokenResponseTransfer());

        $revokeRefreshTokenRequestTransfer->requireRefreshToken()
            ->requireCustomer()
            ->getCustomer()
            ->requireCustomerReference();

        $refreshTokenCriteriaFilterTransfer = (new RefreshTokenCriteriaFilterTransfer())
            ->setIdentifier($revokeRefreshTokenRequestTransfer->getRefreshToken())
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomer()->getCustomerReference())
            ->setRevokedAt(null);

        $oauthRefreshTokenTransfer = $this->oauthRepository->findRefreshToken($refreshTokenCriteriaFilterTransfer);

        if (!$oauthRefreshTokenTransfer) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(false)
                ->setError(
                    (new RefreshTokenErrorTransfer())
                        ->setMessage(sprintf(static::REFRESH_TOKEN_INVALID_ERROR_MESSAGE, $revokeRefreshTokenRequestTransfer->getRefreshToken()))
                        ->setErrorType(static::REFRESH_TOKEN_INVALID_ERROR_TYPE)
                );
        }

        $oauthRefreshTokenTransfer->setRevokedAt((new DateTime())->format("Y-m-d H:i:s.u"));

        $this->oauthEntityManager->revokeRefreshToken($oauthRefreshTokenTransfer);

        return $revokeRefreshTokenResponseTransfer
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshTokensByCustomer(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenResponseTransfer = (new RevokeRefreshTokenResponseTransfer());

        $revokeRefreshTokenRequestTransfer->requireCustomer();

        $refreshTokenCriteriaFilterTransfer = (new RefreshTokenCriteriaFilterTransfer())
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomer()->getCustomerReference())
            ->setRevokedAt(null);

        $oauthRefreshTokenTransfers = $this->oauthRepository->findRefreshTokens($refreshTokenCriteriaFilterTransfer)
            ->getOauthRefreshTokens();

        $this->getTransactionHandler()->handleTransaction(function () use ($oauthRefreshTokenTransfers): void {
            $this->executeRevokeRefreshTokensTransaction($oauthRefreshTokenTransfers);
        });

         return $revokeRefreshTokenResponseTransfer
            ->setIsSuccessful(true);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
     *
     * @return void
     */
    protected function executeRevokeRefreshTokensTransaction(ArrayObject $oauthRefreshTokenTransfers): void
    {
        foreach ($oauthRefreshTokenTransfers as $oauthRefreshTokenTransfer) {
            $oauthRefreshTokenTransfer->setRevokedAt((new DateTime())->format("Y-m-d H:i:s.u"));
            $this->oauthEntityManager->revokeRefreshToken($oauthRefreshTokenTransfer);
        }
    }
}
