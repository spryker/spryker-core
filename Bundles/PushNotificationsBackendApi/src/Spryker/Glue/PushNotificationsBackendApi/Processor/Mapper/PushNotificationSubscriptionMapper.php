<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiPushNotificationGroupsAttributesTransfer;
use Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;

class PushNotificationSubscriptionMapper implements PushNotificationSubscriptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer
     */
    public function mapPushNotificationSubscriptionTransferToApiPushNotificationSubscriptionsAttributesTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
    ): ApiPushNotificationSubscriptionsAttributesTransfer {
        $apiPushNotificationSubscriptionsAttributesTransfer->fromArray(
            $pushNotificationSubscriptionTransfer->toArray(),
            true,
        );

        $pushNotificationProviderName = $pushNotificationSubscriptionTransfer->getProviderOrFail()->getNameOrFail();
        $apiPushNotificationGroupsAttributesTransfer = (new ApiPushNotificationGroupsAttributesTransfer())->fromArray(
            $pushNotificationSubscriptionTransfer->getGroupOrFail()->toArray(),
            true,
        );

        return $apiPushNotificationSubscriptionsAttributesTransfer
            ->setProviderName($pushNotificationProviderName)
            ->setGroup($apiPushNotificationGroupsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapApiPushNotificationSubscriptionsAttributesTransferToPushNotificationSubscriptionTransfer(
        ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        $apiPushNotificationSubscriptionsAttributesData = array_filter(
            $apiPushNotificationSubscriptionsAttributesTransfer->modifiedToArray(),
            function ($value) {
                return $value !== null;
            },
        );

        $pushNotificationSubscriptionTransfer = $pushNotificationSubscriptionTransfer->fromArray(
            $apiPushNotificationSubscriptionsAttributesData,
            true,
        );

        $pushNotificationProviderTransfer = $this->mapApiPushNotificationSubscriptionsAttributesTransferToPushNotificationProviderTransfer(
            $apiPushNotificationSubscriptionsAttributesTransfer,
            new PushNotificationProviderTransfer(),
        );

        $pushNotificationGroupTransfer = $this->mapApiPushNotificationSubscriptionsAttributesTransferToPushNotificationGroupTransfer(
            $apiPushNotificationSubscriptionsAttributesTransfer,
            new PushNotificationGroupTransfer(),
        );

        return $pushNotificationSubscriptionTransfer
            ->setProvider($pushNotificationProviderTransfer)
            ->setGroup($pushNotificationGroupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    protected function mapApiPushNotificationSubscriptionsAttributesTransferToPushNotificationProviderTransfer(
        ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer,
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer {
        $pushNotificationProviderName = $apiPushNotificationSubscriptionsAttributesTransfer->getProviderNameOrFail();

        return $pushNotificationProviderTransfer->setName($pushNotificationProviderName);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationGroupTransfer $pushNotificationGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer
     */
    protected function mapApiPushNotificationSubscriptionsAttributesTransferToPushNotificationGroupTransfer(
        ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer,
        PushNotificationGroupTransfer $pushNotificationGroupTransfer
    ): PushNotificationGroupTransfer {
        return $pushNotificationGroupTransfer->fromArray(
            $apiPushNotificationSubscriptionsAttributesTransfer->getGroupOrFail()->toArray(),
            true,
        );
    }
}
