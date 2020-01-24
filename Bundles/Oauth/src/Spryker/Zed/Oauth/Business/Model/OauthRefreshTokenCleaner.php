<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use ArrayObject;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface;
use Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface;

class OauthRefreshTokenCleaner implements OauthRefreshTokenCleanerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface
     */
    protected $oauthEntityManager;

    /**
     * @var \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface
     */
    protected $oauthRepository;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     */
    public function __construct(OauthRepositoryInterface $oauthRepository, OauthEntityManagerInterface $oauthEntityManager)
    {
        $this->oauthRepository = $oauthRepository;
        $this->oauthEntityManager = $oauthEntityManager;
    }

    /**
     * @return void
     */
    public function cleanExpiredRefreshTokens(): void
    {
        $oauthRefreshTokenTransfers = $this->oauthRepository->getExpiredRefreshTokens()->getOauthRefreshTokens();

        $this->getTransactionHandler()->handleTransaction(function () use ($oauthRefreshTokenTransfers): void {
            $this->executeDeleteRefreshTokensTransaction($oauthRefreshTokenTransfers);
        });
    }

    /**
     * @param \ArrayObject $oauthRefreshTokenTransfers
     *
     * @return void
     */
    protected function executeDeleteRefreshTokensTransaction(ArrayObject $oauthRefreshTokenTransfers): void
    {
        foreach ($oauthRefreshTokenTransfers as $oauthRefreshTokenTransfer) {
            $this->oauthEntityManager->deleteRefreshTokenByIdentifier($oauthRefreshTokenTransfer->getIdentifier());
        }
    }
}
