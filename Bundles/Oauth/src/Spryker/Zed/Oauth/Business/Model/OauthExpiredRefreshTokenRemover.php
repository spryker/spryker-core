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

class OauthExpiredRefreshTokenRemover implements OauthExpiredRefreshTokenRemoverInterface
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
    protected $presentDateTime;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \DateTime $presentDateTime
     */
    public function __construct(
        OauthEntityManagerInterface $oauthEntityManager,
        OauthConfig $oauthConfig,
        DateTime $presentDateTime
    ) {
        $this->oauthEntityManager = $oauthEntityManager;
        $this->oauthConfig = $oauthConfig;
        $this->presentDateTime = $presentDateTime;
    }

    /**
     * @return int|null
     */
    public function deleteExpiredRefreshTokens(): ?int
    {
        $refreshTokenRetentionInterval = new DateInterval($this->oauthConfig->getRefreshTokenRetention());

        return $this->oauthEntityManager->deleteExpiredRefreshTokens($this->getExpiresAt($refreshTokenRetentionInterval));
    }

    /**
     * @param \DateInterval $refreshTokenRetentionInterval
     *
     * @return string
     */
    protected function getExpiresAt(DateInterval $refreshTokenRetentionInterval): string
    {
        return $this->presentDateTime
            ->add($refreshTokenRetentionInterval)
            ->format('Y-m-d H:i:s');
    }
}
