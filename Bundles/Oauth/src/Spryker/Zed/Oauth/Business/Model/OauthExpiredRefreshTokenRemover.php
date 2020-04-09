<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Spryker\Zed\Oauth\OauthConfig;

class OauthExpiredRefreshTokenRemover implements OauthExpiredRefreshTokenRemoverInterface
{
    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @var \DateTime
     */
    protected $presentDateTime;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthExpiredRefreshTokenRemoverPluginInterface[]
     */
    protected $oauthExpiredRefreshTokenRemoverPlugins;

    /**
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \DateTime $presentDateTime
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthExpiredRefreshTokenRemoverPluginInterface[] $oauthExpiredRefreshTokenRemoverPlugins
     */
    public function __construct(
        OauthConfig $oauthConfig,
        DateTime $presentDateTime,
        array $oauthExpiredRefreshTokenRemoverPlugins
    ) {
        $this->oauthConfig = $oauthConfig;
        $this->presentDateTime = $presentDateTime;
        $this->oauthExpiredRefreshTokenRemoverPlugins = $oauthExpiredRefreshTokenRemoverPlugins;
    }

    /**
     * @return int|null
     */
    public function deleteExpiredRefreshTokens(): ?int
    {
        $refreshTokenRetentionInterval = new DateInterval($this->oauthConfig->getRefreshTokenRetentionInterval());

        $deletedExpiredRefreshTokens = 0;
        $oauthTokenCriteriaFilterTransfer = (new OauthTokenCriteriaFilterTransfer())->setExpiresAt($this->getExpiresAt($refreshTokenRetentionInterval));
        foreach ($this->oauthExpiredRefreshTokenRemoverPlugins as $expiredRefreshTokenRemoverPlugin) {
            $deletedExpiredRefreshTokens += $expiredRefreshTokenRemoverPlugin->deleteExpiredRefreshTokens($oauthTokenCriteriaFilterTransfer);
        }

        return $deletedExpiredRefreshTokens;
    }

    /**
     * @param \DateInterval $refreshTokenRetentionInterval
     *
     * @return string
     */
    protected function getExpiresAt(DateInterval $refreshTokenRetentionInterval): string
    {
        return $this->presentDateTime
            ->sub($refreshTokenRetentionInterval)
            ->format('Y-m-d H:i:s');
    }
}
