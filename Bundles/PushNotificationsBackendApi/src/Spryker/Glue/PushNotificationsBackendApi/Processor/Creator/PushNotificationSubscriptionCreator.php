<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapperInterface;

class PushNotificationSubscriptionCreator implements PushNotificationSubscriptionCreatorInterface
{
    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface
     */
    protected PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapperInterface
     */
    protected PushNotificationSubscriptionResourceMapperInterface $pushNotificationSubscriptionResourceMapper;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\ResponseCreatorInterface
     */
    protected ResponseCreatorInterface $responseCreator;

    /**
     * @param \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapperInterface $pushNotificationSubscriptionResourceMapper
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\ResponseCreatorInterface $responseCreator
     */
    public function __construct(
        PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade,
        PushNotificationSubscriptionResourceMapperInterface $pushNotificationSubscriptionResourceMapper,
        ResponseCreatorInterface $responseCreator
    ) {
        $this->pushNotificationFacade = $pushNotificationFacade;
        $this->pushNotificationSubscriptionResourceMapper = $pushNotificationSubscriptionResourceMapper;
        $this->responseCreator = $responseCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationSubscription(
        ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $pushNotificationSubscriptionTransfer = $this
            ->pushNotificationSubscriptionResourceMapper
            ->mapApiPushNotificationSubscriptionAttributesTransferToPushNotificationSubscriptionTransfer(
                $apiPushNotificationSubscriptionAttributesTransfer,
                new PushNotificationSubscriptionTransfer(),
            );
        $pushNotificationSubscriptionTransfer = $this
            ->pushNotificationSubscriptionResourceMapper
            ->mapGlueRequestTransferToPushNotificationSubscriptionTransfer(
                $glueRequestTransfer,
                $pushNotificationSubscriptionTransfer,
            );

        $pushNotificationSubscriptionCollectionRequestTransfer = (new PushNotificationSubscriptionCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPushNotificationSubscriptions(new ArrayObject([$pushNotificationSubscriptionTransfer]));

        $pushNotificationSubscriptionCollectionResponseTransfer = $this
            ->pushNotificationFacade
            ->createPushNotificationSubscriptionCollection($pushNotificationSubscriptionCollectionRequestTransfer);

        if ($pushNotificationSubscriptionCollectionResponseTransfer->getErrors()->count() !== 0) {
            return $this->responseCreator->createPushNotificationSubscriptionErrorResponse(
                $pushNotificationSubscriptionCollectionResponseTransfer->getErrors(),
                $glueRequestTransfer,
            );
        }

        return $this->responseCreator->createPushNotificationSubscriptionResponse(
            $pushNotificationSubscriptionCollectionResponseTransfer->getPushNotificationSubscriptions(),
        );
    }
}
