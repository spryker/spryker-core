<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Reader;

use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;

class PushNotificationProviderReader implements PushNotificationProviderReaderInterface
{
    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface
     */
    protected PushNotificationRepositoryInterface $pushNotificationRepository;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface $pushNotificationRepository
     */
    public function __construct(PushNotificationRepositoryInterface $pushNotificationRepository)
    {
        $this->pushNotificationRepository = $pushNotificationRepository;
    }

    /**
     * @return array<string, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    public function getPushNotificationProviderTransfersIndexedByName(): array
    {
        $pushNotificationProviderCollectionTransfer = $this->pushNotificationRepository->getPushNotificationProviderCollection(
            new PushNotificationProviderCriteriaTransfer(),
        );
        $pushNotificationProviderTransfersIndexedByName = [];
        foreach ($pushNotificationProviderCollectionTransfer->getPushNotificationProviders() as $pushNotificationProviderTransfer) {
            $pushNotificationProviderTransfersIndexedByName[$pushNotificationProviderTransfer->getNameOrFail()] = $pushNotificationProviderTransfer;
        }

        return $pushNotificationProviderTransfersIndexedByName;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    public function getPushNotificationProviderCollection(
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer {
        return $this
            ->pushNotificationRepository
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);
    }
}
