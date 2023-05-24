<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiPushNotificationGroupsAttributesTransfer;
use Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationUserTransfer;

class PushNotificationSubscriptionResourceMapper implements PushNotificationSubscriptionResourceMapperInterface
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
        return $apiPushNotificationSubscriptionsAttributesTransfer
            ->setProviderName(
                $pushNotificationSubscriptionTransfer->getProviderOrFail()->getNameOrFail(),
            )
            ->setGroup(
                (new ApiPushNotificationGroupsAttributesTransfer())
                    ->fromArray($pushNotificationSubscriptionTransfer->getGroupOrFail()->toArray(), true),
            )
            ->setPayload(
                $pushNotificationSubscriptionTransfer->getPayload(),
            );
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
        if (!$pushNotificationSubscriptionTransfer->getProvider()) {
            $pushNotificationSubscriptionTransfer->setProvider(new PushNotificationProviderTransfer());
        }
        $pushNotificationSubscriptionTransfer->getProviderOrFail()->setName(
            $apiPushNotificationSubscriptionsAttributesTransfer->getProviderName(),
        );
        if (!$pushNotificationSubscriptionTransfer->getGroup()) {
            $pushNotificationSubscriptionTransfer->setGroup(new PushNotificationGroupTransfer());
        }
        $pushNotificationSubscriptionTransfer->getGroupOrFail()->fromArray(
            $apiPushNotificationSubscriptionsAttributesTransfer->getGroupOrFail()->toArray(),
            true,
        );
        $pushNotificationSubscriptionTransfer->setPayload(
            $apiPushNotificationSubscriptionsAttributesTransfer->getPayload(),
        );

        return $pushNotificationSubscriptionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapGlueRequestTransferToPushNotificationSubscriptionTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        if (!$pushNotificationSubscriptionTransfer->getUser()) {
            $pushNotificationSubscriptionTransfer->setUser(new PushNotificationUserTransfer());
        }
        $pushNotificationSubscriptionTransfer->getUserOrFail()->setReference(
            (string)$glueRequestTransfer->getRequestUserOrFail()->getSurrogateIdentifierOrFail(),
        );
        $pushNotificationSubscriptionTransfer->getUserOrFail()->setUuid(
            $glueRequestTransfer->getRequestUserOrFail()->getNaturalIdentifierOrFail(),
        );
        if ($glueRequestTransfer->getRequestUserOrFail()->getScopes()) {
            $scope = current($glueRequestTransfer->getRequestUserOrFail()->getScopes());
            $pushNotificationSubscriptionTransfer->getUserOrFail()->setType($scope);
        }

        return $pushNotificationSubscriptionTransfer;
    }
}
