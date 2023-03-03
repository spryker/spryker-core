<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiPushNotificationGroupAttributesTransfer;
use Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationUserTransfer;

class PushNotificationSubscriptionResourceMapper implements PushNotificationSubscriptionResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer
     */
    public function mapPushNotificationSubscriptionTransferToApiPushNotificationSubscriptionAttributesTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer
    ): ApiPushNotificationSubscriptionAttributesTransfer {
        return $apiPushNotificationSubscriptionAttributesTransfer
            ->setProviderName(
                $pushNotificationSubscriptionTransfer->getProviderOrFail()->getNameOrFail(),
            )
            ->setGroup(
                (new ApiPushNotificationGroupAttributesTransfer())
                    ->fromArray($pushNotificationSubscriptionTransfer->getGroupOrFail()->toArray(), true),
            )
            ->setPayload(
                $pushNotificationSubscriptionTransfer->getPayload(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapApiPushNotificationSubscriptionAttributesTransferToPushNotificationSubscriptionTransfer(
        ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        if (!$pushNotificationSubscriptionTransfer->getProvider()) {
            $pushNotificationSubscriptionTransfer->setProvider(new PushNotificationProviderTransfer());
        }
        $pushNotificationSubscriptionTransfer->getProviderOrFail()->setName(
            $apiPushNotificationSubscriptionAttributesTransfer->getProviderName(),
        );
        if (!$pushNotificationSubscriptionTransfer->getGroup()) {
            $pushNotificationSubscriptionTransfer->setGroup(new PushNotificationGroupTransfer());
        }
        $pushNotificationSubscriptionTransfer->getGroupOrFail()->fromArray(
            $apiPushNotificationSubscriptionAttributesTransfer->getGroupOrFail()->toArray(),
            true,
        );
        $pushNotificationSubscriptionTransfer->setPayload(
            $apiPushNotificationSubscriptionAttributesTransfer->getPayload(),
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
        if ($glueRequestTransfer->getRequestUserOrFail()->getScopes()) {
            $scope = current($glueRequestTransfer->getRequestUserOrFail()->getScopes());
            $pushNotificationSubscriptionTransfer->getUserOrFail()->setType($scope);
        }

        return $pushNotificationSubscriptionTransfer;
    }
}
