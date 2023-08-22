<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationSubscriptionResponseBuilder implements PushNotificationSubscriptionResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig
     */
    protected PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapperInterface
     */
    protected PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper;

    /**
     * @param \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper
     */
    public function __construct(
        PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig,
        PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper
    ) {
        $this->pushNotificationsBackendApiConfig = $pushNotificationsBackendApiConfig;
        $this->pushNotificationSubscriptionMapper = $pushNotificationSubscriptionMapper;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationSubscriptionResponse(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($pushNotificationSubscriptionTransfers as $pushNotificationSubscriptionTransfer) {
            $glueResponseTransfer->addResource(
                $this->createPushNotificationSubscriptionResourceTransfer($pushNotificationSubscriptionTransfer),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createPushNotificationSubscriptionResourceTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): GlueResourceTransfer {
        $pushNotificationSubscriptionsBackendApiAttributesTransfer = $this->pushNotificationSubscriptionMapper
            ->mapPushNotificationSubscriptionTransferToPushNotificationSubscriptionsBackendApiAttributesTransfer(
                $pushNotificationSubscriptionTransfer,
                new PushNotificationSubscriptionsBackendApiAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($pushNotificationSubscriptionTransfer->getUuidOrFail())
            ->setType(PushNotificationsBackendApiConfig::RESOURCE_PUSH_NOTIFICATION_SUBSCRIPTIONS)
            ->setAttributes($pushNotificationSubscriptionsBackendApiAttributesTransfer);
    }
}
