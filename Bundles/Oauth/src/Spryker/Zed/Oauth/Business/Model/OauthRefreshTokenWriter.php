<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use ArrayObject;
use DateTime;
use Exception;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class OauthRefreshTokenWriter implements OauthRefreshTokenWriterInterface
{
    use TransactionTrait;
    use CryptTrait;

    protected const REFRESH_TOKEN_INVALID_ERROR_MESSAGE = 'Invalid Refresh Token';
    protected const REFRESH_TOKEN_NOT_FOUND_ERROR_MESSAGE = 'Refresh Token not found';

    protected const KEY_REFRESH_TOKEN_ID = 'refresh_token_id';
    protected const KEY_ACCESS_TOKEN_ID = 'access_token_id';
    protected const KEY_EXPIRE_TIME = 'expire_time';

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
     * @param string|\Defuse\Crypto\Key $encryptionKey
     */
    public function __construct(
        OauthRepositoryInterface $oauthRepository,
        OauthEntityManagerInterface $oauthEntityManager,
        $encryptionKey
    ) {
        $this->oauthRepository = $oauthRepository;
        $this->oauthEntityManager = $oauthEntityManager;
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeConcreteRefreshToken(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenResponseTransfer = new RevokeRefreshTokenResponseTransfer();

        $revokeRefreshTokenRequestTransfer->requireRefreshToken()
            ->requireCustomer()
            ->getCustomer()
            ->requireCustomerReference();

        try {
            $encryptedRefreshTokenTransfer = $this->decryptRefreshToken($revokeRefreshTokenRequestTransfer->getRefreshToken());
        } catch (OAuthServerException $exception) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(false)
                ->setError(static::REFRESH_TOKEN_INVALID_ERROR_MESSAGE);
        }

        $oauthTokenCriteriaFilterTransfer = (new OauthTokenCriteriaFilterTransfer())
            ->setIdentifier($encryptedRefreshTokenTransfer->getIdentifier())
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomer()->getCustomerReference())
            ->setRevokedAt(null);

        $oauthRefreshTokenTransfer = $this->oauthRepository->findRefreshToken($oauthTokenCriteriaFilterTransfer);

        if (!$oauthRefreshTokenTransfer) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(false)
                ->setError(static::REFRESH_TOKEN_NOT_FOUND_ERROR_MESSAGE);
        }

        $oauthRefreshTokenTransfer->setRevokedAt((new DateTime())->format("Y-m-d H:i:s"));

        $this->getTransactionHandler()->handleTransaction(function () use ($oauthRefreshTokenTransfer, $encryptedRefreshTokenTransfer): void {
            $this->oauthEntityManager->revokeRefreshToken($oauthRefreshTokenTransfer);
            $this->oauthEntityManager->deleteAccessTokenByIdentifier($encryptedRefreshTokenTransfer->getAccessTokenIdentifier());
        });

        return $revokeRefreshTokenResponseTransfer->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshTokensByCustomer(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenResponseTransfer = new RevokeRefreshTokenResponseTransfer();

        $revokeRefreshTokenRequestTransfer->requireCustomer()
            ->getCustomer()
            ->requireCustomerReference();

        $oauthTokenCriteriaFilterTransfer = (new OauthTokenCriteriaFilterTransfer())
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomer()->getCustomerReference());

        $oauthAccessTokenTransfers = $this->oauthRepository
            ->findAccessTokens($oauthTokenCriteriaFilterTransfer)
            ->getOauthAccessTokens();

        $oauthRefreshTokenTransfers = $this->oauthRepository
            ->findRefreshTokens($oauthTokenCriteriaFilterTransfer->setRevokedAt(null))
            ->getOauthRefreshTokens();

        $this->getTransactionHandler()->handleTransaction(function () use ($oauthRefreshTokenTransfers, $oauthAccessTokenTransfers): void {
            $this->executeRevokeRefreshTokensTransaction($oauthRefreshTokenTransfers);
            $this->executeRevokeAccessTokensTransaction($oauthAccessTokenTransfers);
        });

         return $revokeRefreshTokenResponseTransfer->setIsSuccessful(true);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
     *
     * @return void
     */
    protected function executeRevokeRefreshTokensTransaction(ArrayObject $oauthRefreshTokenTransfers): void
    {
        foreach ($oauthRefreshTokenTransfers as $oauthRefreshTokenTransfer) {
            $oauthRefreshTokenTransfer->setRevokedAt((new DateTime())->format("Y-m-d H:i:s"));
            $this->oauthEntityManager->revokeRefreshToken($oauthRefreshTokenTransfer);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthAccessTokenDataTransfer[] $oauthAccessTokenTransfers
     *
     * @return void
     */
    protected function executeRevokeAccessTokensTransaction(ArrayObject $oauthAccessTokenTransfers): void
    {
        foreach ($oauthAccessTokenTransfers as $oauthAccessTokenDataTransfer) {
            $this->oauthEntityManager->deleteAccessTokenByIdentifier($oauthAccessTokenDataTransfer->getIdentifier());
        }
    }

    /**
     * @param string $refreshToken
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer
     */
    protected function decryptRefreshToken(string $refreshToken): OauthRefreshTokenTransfer
    {
        try {
            $refreshToken = $this->decrypt($refreshToken);
        } catch (Exception $e) {
            throw OAuthServerException::invalidRefreshToken('Cannot decrypt the refresh token', $e);
        }

        $refreshTokenData = json_decode($refreshToken, true);

        $refreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setExpiresAt($refreshTokenData[static::KEY_EXPIRE_TIME])
            ->setIdentifier($refreshTokenData[static::KEY_REFRESH_TOKEN_ID])
            ->setAccessTokenIdentifier($refreshTokenData[static::KEY_ACCESS_TOKEN_ID]);

        return $refreshTokenTransfer;
    }
}
