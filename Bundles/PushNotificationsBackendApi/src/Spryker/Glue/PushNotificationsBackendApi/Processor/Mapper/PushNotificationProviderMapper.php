<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiPushNotificationProvidersAttributesTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;

class PushNotificationProviderMapper implements PushNotificationProviderMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     * @param \Generated\Shared\Transfer\ApiPushNotificationProvidersAttributesTransfer $apiPushNotificationProvidersAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPushNotificationProvidersAttributesTransfer
     */
    public function mapPushNotificationProviderTransferToApiPushNotificationProvidersAttributesTransfer(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer,
        ApiPushNotificationProvidersAttributesTransfer $apiPushNotificationProvidersAttributesTransfer
    ): ApiPushNotificationProvidersAttributesTransfer {
        return $apiPushNotificationProvidersAttributesTransfer->fromArray(
            $pushNotificationProviderTransfer->toArray(),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationProvidersAttributesTransfer $apiPushNotificationProvidersAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function mapApiPushNotificationProvidersAttributesTransferToPushNotificationProviderTransfer(
        ApiPushNotificationProvidersAttributesTransfer $apiPushNotificationProvidersAttributesTransfer,
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer {
        $apiServicePointsAttributesData = array_filter(
            $apiPushNotificationProvidersAttributesTransfer->modifiedToArray(),
            function ($value) {
                return $value !== null;
            },
        );

        return $pushNotificationProviderTransfer->fromArray($apiServicePointsAttributesData, true);
    }
}
