<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use Exception;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;
use League\OAuth2\Server\CryptTrait;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface;
use Spryker\Zed\Oauth\OauthConfig;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class OauthRefreshTokenRevoker implements OauthRefreshTokenRevokerInterface
{
    use CryptTrait;

    protected const REFRESH_TOKEN_INVALID_ERROR_MESSAGE = 'Invalid Refresh Token';
    protected const REFRESH_TOKEN_NOT_FOUND_ERROR_MESSAGE = 'Refresh Token not found';

    protected const KEY_REFRESH_TOKEN_ID = 'refresh_token_id';

    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface
     */
    protected $refreshTokenRepository;

    /**
     * @var \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        OauthRepositoryInterface $oauthRepository,
        OauthToUtilEncodingServiceInterface $utilEncodingService,
        OauthConfig $oauthConfig
    ) {
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->oauthRepository = $oauthRepository;
        $this->utilEncodingService = $utilEncodingService;
        $this->encryptionKey = $oauthConfig->getEncryptionKey();
    }

    /**
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshToken(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenRequestTransfer->requireRefreshToken()
            ->requireCustomerReference();

        $revokeRefreshTokenResponseTransfer = new RevokeRefreshTokenResponseTransfer();

        $decryptedRefreshToken = $this->extractRefreshTokenId($revokeRefreshTokenRequestTransfer->getRefreshToken());
        if (!$decryptedRefreshToken) {
            return $revokeRefreshTokenResponseTransfer
                ->setIsSuccessful(false)
                ->setError(static::REFRESH_TOKEN_INVALID_ERROR_MESSAGE);
        }

        $oauthTokenCriteriaFilterTransfer = (new OauthTokenCriteriaFilterTransfer())
            ->setIdentifier($decryptedRefreshToken)
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomerReference())
            ->setIsRevoked(false);

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
    public function revokeAllRefreshTokens(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenRequestTransfer->requireCustomerReference();

        $oauthTokenCriteriaFilterTransfer = (new OauthTokenCriteriaFilterTransfer())
            ->setCustomerReference($revokeRefreshTokenRequestTransfer->getCustomerReference())
            ->setIsRevoked(false);

        $oauthRefreshTokenTransfers = $this->oauthRepository
            ->getRefreshTokens($oauthTokenCriteriaFilterTransfer)
            ->getOauthRefreshTokens();

        $this->refreshTokenRepository->revokeAllRefreshTokens($oauthRefreshTokenTransfers);

        return (new RevokeRefreshTokenResponseTransfer())->setIsSuccessful(true);
    }

    /**
     * @param string $refreshToken
     *
     * @return string|null
     */
    protected function extractRefreshTokenId(string $refreshToken): ?string
    {
        try {
            $refreshToken = $this->decrypt($refreshToken);
        } catch (Exception $e) {
            return null;
        }

        $refreshTokenData = $this->utilEncodingService->decodeJson($refreshToken, true);

        return $refreshTokenData[static::KEY_REFRESH_TOKEN_ID];
    }
}
