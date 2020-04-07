<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;
use League\OAuth2\Server\CryptTrait;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface;
use Spryker\Zed\Oauth\OauthConfig;

class OauthRefreshTokenRevoker implements OauthRefreshTokenRevokerInterface
{
    use CryptTrait;

    protected const REFRESH_TOKEN_INVALID_ERROR_MESSAGE = 'Invalid Refresh Token';
    protected const REFRESH_TOKEN_NOT_FOUND_ERROR_MESSAGE = 'Refresh Token not found';

    protected const KEY_REFRESH_TOKEN_ID = 'refresh_token_id';

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface
     */
    protected $refreshTokenRepository;

    /**
     * @var \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenReaderPluginInterface[]
     */
    protected $oauthRefreshTokenReaderPlugins;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensReaderPluginInterface[]
     */
    protected $oauthRefreshTokensReaderPlugins;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\Repositories\RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param \Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenReaderPluginInterface[] $oauthRefreshTokenReaderPlugins
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensReaderPluginInterface[] $oauthRefreshTokensReaderPlugins
     */
    public function __construct(
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        OauthToUtilEncodingServiceInterface $utilEncodingService,
        OauthConfig $oauthConfig,
        array $oauthRefreshTokenReaderPlugins,
        array $oauthRefreshTokensReaderPlugins
    ) {
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->utilEncodingService = $utilEncodingService;
        $this->encryptionKey = $oauthConfig->getEncryptionKey();
        $this->oauthRefreshTokenReaderPlugins = $oauthRefreshTokenReaderPlugins;
        $this->oauthRefreshTokensReaderPlugins = $oauthRefreshTokensReaderPlugins;
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

        $oauthRefreshTokenTransfer = $this->findOauthRefreshToken($oauthTokenCriteriaFilterTransfer);

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

        $oauthRefreshTokenTransfers = $this->getOauthRefreshTokens($oauthTokenCriteriaFilterTransfer);

        if ($oauthRefreshTokenTransfers->count()) {
            $this->refreshTokenRepository->revokeAllRefreshTokens($oauthRefreshTokenTransfers);
        }

        return (new RevokeRefreshTokenResponseTransfer())->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    protected function findOauthRefreshToken(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer
    {
        $oauthRefreshTokenTransfer = null;
        foreach ($this->oauthRefreshTokenReaderPlugins as $oauthRefreshTokenReaderPlugin) {
            if (!$oauthRefreshTokenReaderPlugin->isApplicable($oauthTokenCriteriaFilterTransfer)) {
                continue;
            }

            return $oauthRefreshTokenReaderPlugin->findRefreshToken($oauthTokenCriteriaFilterTransfer);
        }

        return $oauthRefreshTokenTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[]
     */
    protected function getOauthRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ArrayObject
    {
        $oauthRefreshTokens = [];
        foreach ($this->oauthRefreshTokensReaderPlugins as $oauthRefreshTokensReaderPlugin) {
            $receivedOauthRefreshTokens = $oauthRefreshTokensReaderPlugin
                ->getRefreshTokens($oauthTokenCriteriaFilterTransfer)
                ->getOauthRefreshTokens()
                ->getArrayCopy();

            $oauthRefreshTokens = array_merge(
                $oauthRefreshTokens,
                $receivedOauthRefreshTokens
            );
        }

        return new ArrayObject($oauthRefreshTokens);
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
