<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business\Revoker;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface;
use Spryker\Zed\OauthRevoke\Persistence\OauthRevokeRepositoryInterface;

class OauthRefreshTokenRevoker implements OauthRefreshTokenRevokerInterface
{
    /**
     * @var \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface
     */
    protected $oauthRevokeEntityManager;

    /**
     * @var \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeRepositoryInterface
     */
    protected $oauthRevokeRepository;

    /**
     * @param \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface $oauthRevokeEntityManager
     * @param \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeRepositoryInterface $oauthRevokeRepository
     */
    public function __construct(
        OauthRevokeEntityManagerInterface $oauthRevokeEntityManager,
        OauthRevokeRepositoryInterface $oauthRevokeRepository
    ) {
        $this->oauthRevokeEntityManager = $oauthRevokeEntityManager;
        $this->oauthRevokeRepository = $oauthRevokeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    public function revokeRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void
    {
        if ($this->oauthRevokeRepository->isRefreshTokenRevoked($oauthRefreshTokenTransfer)) {
            return;
        }

        $this->oauthRevokeEntityManager->revokeRefreshToken($oauthRefreshTokenTransfer);
    }
}
