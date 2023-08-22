<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\PushNotificationProvidersBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;

interface PushNotificationProviderMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     * @param \Generated\Shared\Transfer\PushNotificationProvidersBackendApiAttributesTransfer $pushNotificationProvidersBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProvidersBackendApiAttributesTransfer
     */
    public function mapPushNotificationProviderTransferToPushNotificationProvidersBackendApiAttributesTransfer(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer,
        PushNotificationProvidersBackendApiAttributesTransfer $pushNotificationProvidersBackendApiAttributesTransfer
    ): PushNotificationProvidersBackendApiAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProvidersBackendApiAttributesTransfer $pushNotificationProvidersBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function mapPushNotificationProvidersBackendApiAttributesTransferToPushNotificationProviderTransfer(
        PushNotificationProvidersBackendApiAttributesTransfer $pushNotificationProvidersBackendApiAttributesTransfer,
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer;
}
