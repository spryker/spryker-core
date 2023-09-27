<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationSubscriptionResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationSubscriptionCreator implements PushNotificationSubscriptionCreatorInterface
{
    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface
     */
    protected PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationSubscriptionResponseBuilderInterface
     */
    protected PushNotificationSubscriptionResponseBuilderInterface $pushNotificationSubscriptionResponseBuilder;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapperInterface
     */
    protected PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @param \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationSubscriptionResponseBuilderInterface $pushNotificationSubscriptionResponseBuilder
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     */
    public function __construct(
        PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade,
        PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper,
        PushNotificationSubscriptionResponseBuilderInterface $pushNotificationSubscriptionResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->pushNotificationFacade = $pushNotificationFacade;
        $this->pushNotificationSubscriptionMapper = $pushNotificationSubscriptionMapper;
        $this->pushNotificationSubscriptionResponseBuilder = $pushNotificationSubscriptionResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationSubscription(
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        /** @var \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer|null $pushNotificationSubscriptionsBackendApiAttributesTransfer */
        $pushNotificationSubscriptionsBackendApiAttributesTransfer = $glueRequestTransfer->getResourceOrFail()->getAttributes();

        if (!$pushNotificationSubscriptionsBackendApiAttributesTransfer) {
            return $this->errorResponseBuilder->createErrorResponseFromErrorMessage(
                PushNotificationsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
                $glueRequestTransfer->getLocale(),
            );
        }

        $pushNotificationSubscriptionTransfer = $this->pushNotificationSubscriptionMapper
            ->mapPushNotificationSubscriptionsBackendApiAttributesTransferToPushNotificationSubscriptionTransfer(
                $pushNotificationSubscriptionsBackendApiAttributesTransfer,
                new PushNotificationSubscriptionTransfer(),
            );

        $pushNotificationSubscriptionTransfer = $this->pushNotificationSubscriptionMapper
            ->mapGlueRequestTransferToPushNotificationSubscriptionTransfer(
                $glueRequestTransfer,
                $pushNotificationSubscriptionTransfer,
            );

        $pushNotificationSubscriptionCollectionRequestTransfer = $this->createPushNotificationProviderCollectionRequestTransfer($pushNotificationSubscriptionTransfer);
        $pushNotificationSubscriptionCollectionResponseTransfer = $this->pushNotificationFacade
            ->createPushNotificationSubscriptionCollection($pushNotificationSubscriptionCollectionRequestTransfer);

        return $this->createGlueResponseTransfer(
            $pushNotificationSubscriptionCollectionResponseTransfer,
            $glueRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer $pushNotificationSubscriptionCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function createGlueResponseTransfer(
        PushNotificationSubscriptionCollectionResponseTransfer $pushNotificationSubscriptionCollectionResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $errorTransfers = $pushNotificationSubscriptionCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->errorResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->pushNotificationSubscriptionResponseBuilder->createPushNotificationSubscriptionResponse(
            $pushNotificationSubscriptionCollectionResponseTransfer->getPushNotificationSubscriptions(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer
     */
    protected function createPushNotificationProviderCollectionRequestTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionCollectionRequestTransfer {
        return (new PushNotificationSubscriptionCollectionRequestTransfer())
            ->addPushNotificationSubscription($pushNotificationSubscriptionTransfer)
            ->setIsTransactional(true);
    }
}
