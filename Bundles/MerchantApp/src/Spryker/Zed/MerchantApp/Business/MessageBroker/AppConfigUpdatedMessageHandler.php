<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MessageBroker;

use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface;
use Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface;

class AppConfigUpdatedMessageHandler implements AppConfigUpdatedMessageHandlerInterface
{
    protected MerchantAppRepositoryInterface $merchantAppRepository;

    protected MerchantAppEntityManagerInterface $merchantAppEntityManager;

    /**
     * @param \Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface $merchantAppRepository
     * @param \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface $merchantAppEntityManager
     */
    public function __construct(
        MerchantAppRepositoryInterface $merchantAppRepository,
        MerchantAppEntityManagerInterface $merchantAppEntityManager
    ) {
        $this->merchantAppRepository = $merchantAppRepository;
        $this->merchantAppEntityManager = $merchantAppEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigUpdatedTransfer $appConfigUpdatedTransfer
     *
     * @return void
     */
    public function handleAppConfigUpdatedTransfer(AppConfigUpdatedTransfer $appConfigUpdatedTransfer): void
    {
        if ($appConfigUpdatedTransfer->getIsActiveOrFail()) {
            return;
        }

        $this->merchantAppEntityManager->deleteMerchantAppOnboardingByAppIdentifier($appConfigUpdatedTransfer->getAppIdentifierOrFail());
    }
}
