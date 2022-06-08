<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Business\Cache;

use DateTime;
use Generated\Shared\Transfer\AccessTokenCacheTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Zed\OauthClient\Persistence\OauthClientEntityManagerInterface;
use Spryker\Zed\OauthClient\Persistence\OauthClientRepositoryInterface;

class AccessTokenDatabaseCache implements AccessTokenCacheInterface
{
    /**
     * @var \Spryker\Zed\OauthClient\Persistence\OauthClientEntityManagerInterface
     */
    protected $oauthClientEntityManager;

    /**
     * @var \Spryker\Zed\OauthClient\Persistence\OauthClientRepositoryInterface
     */
    protected $oauthClientRepository;

    /**
     * @param \Spryker\Zed\OauthClient\Persistence\OauthClientEntityManagerInterface $oauthClientEntityManager
     * @param \Spryker\Zed\OauthClient\Persistence\OauthClientRepositoryInterface $oauthClientRepository
     */
    public function __construct(
        OauthClientEntityManagerInterface $oauthClientEntityManager,
        OauthClientRepositoryInterface $oauthClientRepository
    ) {
        $this->oauthClientEntityManager = $oauthClientEntityManager;
        $this->oauthClientRepository = $oauthClientRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function get(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer
    {
        $accessTokenRequestHash = $this->getAccessTokenRequestHash($accessTokenRequestTransfer);

        $accessTokenCacheTransfer = $this->oauthClientRepository
            ->findAccessTokenCacheByCacheKey($accessTokenRequestHash);

        if ($accessTokenCacheTransfer === null) {
            return (new AccessTokenResponseTransfer())->setIsSuccessful(false);
        }

        if ($accessTokenCacheTransfer->getExpiresAt() <= (new DateTime())->getTimestamp()) {
            $this->oauthClientEntityManager->deleteAccessTokenCacheEntityByCacheKey($accessTokenRequestHash);

            return (new AccessTokenResponseTransfer())->setIsSuccessful(false);
        }

        return (new AccessTokenResponseTransfer())
            ->setIsSuccessful(true)
            ->setAccessToken($accessTokenCacheTransfer->getAccessToken())
            ->setExpiresAt($accessTokenCacheTransfer->getExpiresAt());
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     * @param \Generated\Shared\Transfer\AccessTokenResponseTransfer $accessTokenResponseTransfer
     *
     * @return void
     */
    public function set(
        AccessTokenRequestTransfer $accessTokenRequestTransfer,
        AccessTokenResponseTransfer $accessTokenResponseTransfer
    ): void {
        if (!$accessTokenResponseTransfer->getIsSuccessful()) {
            return;
        }

        $accessTokenCacheTransfer = (new AccessTokenCacheTransfer())
            ->setCacheKey($this->getAccessTokenRequestHash($accessTokenRequestTransfer))
            ->setAccessToken($accessTokenResponseTransfer->getAccessToken())
            ->setExpiresAt($accessTokenResponseTransfer->getExpiresAt());

        $this->oauthClientEntityManager->saveAccessTokenCache($accessTokenCacheTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return string
     */
    protected function getAccessTokenRequestHash(AccessTokenRequestTransfer $accessTokenRequestTransfer): string
    {
        return sha1(serialize($accessTokenRequestTransfer->modifiedToArray()));
    }
}
