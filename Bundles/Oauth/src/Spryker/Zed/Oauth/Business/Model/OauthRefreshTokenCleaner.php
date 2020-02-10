<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use DateTime;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Oauth\OauthConfig;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;

class OauthRefreshTokenCleaner implements OauthRefreshTokenCleanerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface
     */
    protected $oauthEntityManager;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        OauthEntityManagerInterface $oauthEntityManager,
        OauthConfig $oauthConfig
    ) {
        $this->oauthEntityManager = $oauthEntityManager;
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @return int|null
     */
    public function deleteExpiredRefreshTokens(): ?int
    {
        $refreshTokenRetentionInterval = $this->oauthConfig->getRefreshTokenRetentionInterval();
        if ($refreshTokenRetentionInterval === null) {
            return null;
        }

        $expiredAt = (new DateTime())
            ->add($refreshTokenRetentionInterval)
            ->format('Y-m-d H:i:s');

        return $this->oauthEntityManager->deleteExpiredRefreshTokens($expiredAt);
    }
}
