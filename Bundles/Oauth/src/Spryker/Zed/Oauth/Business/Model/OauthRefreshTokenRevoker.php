<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Oauth\OauthConfig;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class OauthRefreshTokenRevoker implements OauthRefreshTokenRevokerInterface
{
    use TransactionTrait;
    use CryptTrait;

    protected const REFRESH_TOKEN_INVALID_ERROR_MESSAGE = 'Invalid Refresh Token';
    protected const REFRESH_TOKEN_NOT_FOUND_ERROR_MESSAGE = 'Refresh Token not found';

    protected const KEY_REFRESH_TOKEN_ID = 'refresh_token_id';
    protected const KEY_ACCESS_TOKEN_ID = 'access_token_id';
    protected const KEY_EXPIRE_TIME = 'expire_time';

    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @var \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface
     */
    protected $refreshTokenRepository;

    /**
     * @param \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        OauthRepositoryInterface $oauthRepository,
        OauthConfig $oauthConfig
    ) {
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->oauthRepository = $oauthRepository;
        $this->encryptionKey = $oauthConfig->getEncryptionKey();
    }

    /**
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshToken(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenResponseTransfer = new RevokeRefreshTokenResponseTransfer();

        $revokeRefreshTokenRequestTransfer->requireRefreshToken()
            ->requireCustomerReference();

        $decryptedRefreshToken = $this->decryptRefreshToken($revokeRefreshTokenRequestTransfer->getRefreshToken());
        if (!$decryptedRefreshToken) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(false)
                ->setError(static::REFRESH_TOKEN_INVALID_ERROR_MESSAGE);
        }

        $oauthTokenCriteriaFilterTransfer = (new OauthTokenCriteriaFilterTransfer())
            ->setIdentifier($decryptedRefreshToken)
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomerReference())
            ->setRevokedAt(null);

        $oauthRefreshTokenTransfer = $this->oauthRepository->findRefreshToken($oauthTokenCriteriaFilterTransfer);
        if (!$oauthRefreshTokenTransfer) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(false)
                ->setError(static::REFRESH_TOKEN_NOT_FOUND_ERROR_MESSAGE);
        }

        $this->refreshTokenRepository->revokeRefreshToken($oauthRefreshTokenTransfer->getIdentifier());

        return $revokeRefreshTokenResponseTransfer->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshTokens(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenRequestTransfer->requireCustomerReference();

        $oauthTokenCriteriaFilterTransfer = (new OauthTokenCriteriaFilterTransfer())
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomerReference())
            ->setRevokedAt(null);

        $oauthRefreshTokenTransfers = $this->oauthRepository
            ->findRefreshTokens($oauthTokenCriteriaFilterTransfer)
            ->getOauthRefreshTokens();

        $this->getTransactionHandler()->handleTransaction(function () use ($oauthRefreshTokenTransfers): void {
            $this->executeRevokeRefreshTokensTransaction($oauthRefreshTokenTransfers);
        });

        return (new RevokeRefreshTokenResponseTransfer())->setIsSuccessful(true);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
     *
     * @return void
     */
    protected function executeRevokeRefreshTokensTransaction(ArrayObject $oauthRefreshTokenTransfers): void
    {
        foreach ($oauthRefreshTokenTransfers as $oauthRefreshTokenTransfer) {
            $this->refreshTokenRepository->revokeRefreshToken($oauthRefreshTokenTransfer->getIdentifier());
        }
    }

    /**
     * @param string $refreshToken
     *
     * @return string|null
     */
    protected function decryptRefreshToken(string $refreshToken): ?string
    {
        try {
            $refreshToken = $this->decrypt($refreshToken);
        } catch (Exception $e) {
            return null;
        }

        $refreshTokenData = (new UtilEncodingService())->decodeJson($refreshToken, true);

        return $refreshTokenData[static::KEY_REFRESH_TOKEN_ID];
    }
}
