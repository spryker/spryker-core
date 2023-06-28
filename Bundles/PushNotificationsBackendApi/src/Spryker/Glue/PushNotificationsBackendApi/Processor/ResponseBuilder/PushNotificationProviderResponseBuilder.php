<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ApiPushNotificationProvidersAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationProviderResponseBuilder implements PushNotificationProviderResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig
     */
    protected PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface
     */
    protected PushNotificationProviderMapperInterface $pushNotificationProviderMapper;

    /**
     * @param \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface $pushNotificationProviderMapper
     */
    public function __construct(
        PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig,
        PushNotificationProviderMapperInterface $pushNotificationProviderMapper
    ) {
        $this->pushNotificationsBackendApiConfig = $pushNotificationsBackendApiConfig;
        $this->pushNotificationProviderMapper = $pushNotificationProviderMapper;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationProviderResponse(
        ArrayObject $pushNotificationProviderTransfers
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($pushNotificationProviderTransfers as $pushNotificationProviderTransfer) {
            $glueResponseTransfer->addResource(
                $this->createPushNotificationProviderResourceTransfer($pushNotificationProviderTransfer),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createPushNotificationProviderResourceTransfer(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): GlueResourceTransfer {
        $apiPushNotificationProvidersAttributesTransfer = $this->pushNotificationProviderMapper
            ->mapPushNotificationProviderTransferToApiPushNotificationProvidersAttributesTransfer(
                $pushNotificationProviderTransfer,
                new ApiPushNotificationProvidersAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($pushNotificationProviderTransfer->getUuidOrFail())
            ->setType(PushNotificationsBackendApiConfig::RESOURCE_PUSH_NOTIFICATION_PROVIDERS)
            ->setAttributes($apiPushNotificationProvidersAttributesTransfer);
    }
}
