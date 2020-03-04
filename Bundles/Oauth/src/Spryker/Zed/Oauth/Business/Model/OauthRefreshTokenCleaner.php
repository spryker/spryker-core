<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use DateInterval;
use DateTime;
use Spryker\Zed\Oauth\OauthConfig;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;

class OauthRefreshTokenCleaner implements OauthRefreshTokenCleanerInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface
     */
    protected $oauthEntityManager;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \DateTime $dateTime
     */
    public function __construct(
        OauthEntityManagerInterface $oauthEntityManager,
        OauthConfig $oauthConfig,
        DateTime $dateTime
    ) {
        $this->oauthEntityManager = $oauthEntityManager;
        $this->oauthConfig = $oauthConfig;
        $this->dateTime = $dateTime;
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

        return $this->oauthEntityManager->deleteExpiredRefreshTokens($this->getExpiresAt($refreshTokenRetentionInterval));
    }

    /**
     * @param \DateInterval|null $refreshTokenRetentionInterval
     *
     * @return string
     */
    protected function getExpiresAt(?DateInterval $refreshTokenRetentionInterval)
    {
        return $this->dateTime
            ->add($refreshTokenRetentionInterval)
            ->format('Y-m-d H:i:s');
    }
}
