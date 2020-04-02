<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use DateInterval;
use DateTime;
use Spryker\Zed\Oauth\Dependency\Facade\OauthToOauthRevokeFacadeInterface;
use Spryker\Zed\Oauth\OauthConfig;

class OauthExpiredRefreshTokenRemover implements OauthExpiredRefreshTokenRemoverInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Dependency\Facade\OauthToOauthRevokeFacadeInterface
     */
    protected $oauthRevokeFacade;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @var \DateTime
     */
    protected $presentDateTime;

    /**
     * @param \Spryker\Zed\Oauth\Dependency\Facade\OauthToOauthRevokeFacadeInterface $oauthRevokeFacade
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \DateTime $presentDateTime
     */
    public function __construct(
        OauthToOauthRevokeFacadeInterface $oauthRevokeFacade,
        OauthConfig $oauthConfig,
        DateTime $presentDateTime
    ) {
        $this->oauthRevokeFacade = $oauthRevokeFacade;
        $this->oauthConfig = $oauthConfig;
        $this->presentDateTime = $presentDateTime;
    }

    /**
     * @return int|null
     */
    public function deleteExpiredRefreshTokens(): ?int
    {
        $refreshTokenRetentionInterval = new DateInterval($this->oauthConfig->getRefreshTokenRetentionInterval());

        return $this->oauthRevokeFacade->deleteExpiredRefreshTokens($this->getExpiresAt($refreshTokenRetentionInterval));
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
