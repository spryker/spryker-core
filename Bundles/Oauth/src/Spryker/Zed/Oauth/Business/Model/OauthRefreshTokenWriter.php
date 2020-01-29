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
use Generated\Shared\Transfer\RefreshTokenCriteriaFilterTransfer;
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

    protected const KEY_REFRESH_TOKEN_ID = 'refresh_token_id';
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
        $revokeRefreshTokenResponseTransfer = (new RevokeRefreshTokenResponseTransfer());

        $revokeRefreshTokenRequestTransfer->requireRefreshToken()
            ->requireCustomer()
            ->getCustomer()
            ->requireCustomerReference();

        try {
            $refreshTokenTransfer = $this->decryptRefreshToken($revokeRefreshTokenRequestTransfer->getRefreshToken());
        } catch (OAuthServerException $exception) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(false)
                ->setError(static::REFRESH_TOKEN_INVALID_ERROR_MESSAGE);
        }

        $refreshTokenCriteriaFilterTransfer = (new RefreshTokenCriteriaFilterTransfer())
            ->setIdentifier($refreshTokenTransfer->getIdentifier())
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomer()->getCustomerReference())
            ->setRevokedAt(null);

        $oauthRefreshTokenTransfer = $this->oauthRepository->findRefreshToken($refreshTokenCriteriaFilterTransfer);

        if (!$oauthRefreshTokenTransfer) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(false)
                ->setError(static::REFRESH_TOKEN_INVALID_ERROR_MESSAGE);
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

        $revokeRefreshTokenRequestTransfer->requireCustomer()
            ->getCustomer()
            ->requireCustomerReference();

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

        $refreshTokenTransfer = (new OauthRefreshTokenTransfer())->fromArray($refreshTokenData, true);
        $refreshTokenTransfer->setExpiresAt($refreshTokenData[static::KEY_EXPIRE_TIME]);
        $refreshTokenTransfer->setIdentifier($refreshTokenData[static::KEY_REFRESH_TOKEN_ID]);

        if ($refreshTokenTransfer->getExpiresAt() < time()) {
            throw OAuthServerException::invalidRefreshToken('Token has expired');
        }

        if ($this->oauthRepository->isRefreshTokenRevoked($refreshTokenTransfer)) {
            throw OAuthServerException::invalidRefreshToken('Token has been revoked');
        }

        return $refreshTokenTransfer;
    }
}
