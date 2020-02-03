<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model;

use ArrayObject;
use DateTime;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Oauth\OauthConfig;
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
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface $oauthRepository
     * @param \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface $oauthEntityManager
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        OauthRepositoryInterface $oauthRepository,
        OauthEntityManagerInterface $oauthEntityManager,
        OauthConfig $oauthConfig
    ) {
        $this->oauthRepository = $oauthRepository;
        $this->oauthEntityManager = $oauthEntityManager;
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @return void
     */
    public function deleteExpiredRefreshTokens(): void
    {
        $expiredAt = (new DateTime())
            ->add($this->oauthConfig->getRefreshTokenRetentionInterval())
            ->format('Y-m-d H:i:s');

        $oauthRefreshTokenTransfers = $this->oauthRepository->getExpiredRefreshTokens($expiredAt)->getOauthRefreshTokens();

        $this->getTransactionHandler()->handleTransaction(function () use ($oauthRefreshTokenTransfers): void {
            $this->executeDeleteRefreshTokensTransaction($oauthRefreshTokenTransfers);
        });
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
     *
     * @return void
     */
    protected function executeDeleteRefreshTokensTransaction(ArrayObject $oauthRefreshTokenTransfers): void
    {
        $identifierList = [];
        foreach ($oauthRefreshTokenTransfers as $oauthRefreshTokenTransfer) {
            $identifierList[] = $oauthRefreshTokenTransfer->getIdentifier();
        }

        $this->oauthEntityManager->deleteRefreshTokenByIdentifierList($identifierList);
    }
}
