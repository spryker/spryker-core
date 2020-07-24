<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business\Creator;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\OauthRevoke\Business\Mapper\OauthRefreshTokenMapperInterface;
use Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface;

class OauthRefreshTokenCreator implements OauthRefreshTokenCreatorInterface
{
    /**
     * @var \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface
     */
    protected $oauthRevokeEntityManager;

    /**
     * @var \Spryker\Zed\OauthRevoke\Business\Mapper\OauthRefreshTokenMapperInterface
     */
    protected $oauthRefreshTokenMapper;

    /**
     * @param \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface $oauthRevokeEntityManager
     * @param \Spryker\Zed\OauthRevoke\Business\Mapper\OauthRefreshTokenMapperInterface $oauthRefreshTokenMapper
     */
    public function __construct(
        OauthRevokeEntityManagerInterface $oauthRevokeEntityManager,
        OauthRefreshTokenMapperInterface $oauthRefreshTokenMapper
    ) {
        $this->oauthRevokeEntityManager = $oauthRevokeEntityManager;
        $this->oauthRefreshTokenMapper = $oauthRefreshTokenMapper;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\OauthRevoke\Business\Creator\OauthRefreshTokenCreator::saveRefreshTokenFromTransfer()} instead.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $oauthRefreshTokenTransfer = $this->oauthRefreshTokenMapper->mapRefreshTokenEntityToOauthRefreshTokenTransfer(
            $refreshTokenEntity,
            new OauthRefreshTokenTransfer()
        );

        $this->oauthRevokeEntityManager->saveRefreshToken($oauthRefreshTokenTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    public function saveRefreshTokenFromTransfer(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void
    {
        $this->oauthRevokeEntityManager->saveRefreshToken($oauthRefreshTokenTransfer);
    }
}
