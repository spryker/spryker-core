<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationProviderReader implements PushNotificationProviderReaderInterface
{
    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface
     */
    protected PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface
     */
    protected PushNotificationProviderResponseBuilderInterface $pushNotificationProviderResponseBuilder;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @param \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface $pushNotificationProviderResponseBuilder
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     */
    public function __construct(
        PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade,
        PushNotificationProviderResponseBuilderInterface $pushNotificationProviderResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->pushNotificationFacade = $pushNotificationFacade;
        $this->pushNotificationProviderResponseBuilder = $pushNotificationProviderResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getPushNotificationProviderCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPagination($glueRequestTransfer->getPagination())
            ->setSortCollection($glueRequestTransfer->getSortings())
            ->setPushNotificationProviderConditions(new PushNotificationProviderConditionsTransfer());

        $pushNotificationProviderTransfers = $this->pushNotificationFacade
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer)
            ->getPushNotificationProviders();

        return $this->pushNotificationProviderResponseBuilder->createPushNotificationProviderResponse($pushNotificationProviderTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getPushNotificationProvider(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->addUuid($glueRequestTransfer->getResourceOrFail()->getIdOrFail());

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        $pushNotificationProviderTransfers = $this->pushNotificationFacade
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer)
            ->getPushNotificationProviders();

        if ($pushNotificationProviderTransfers->count() === 1) {
            return $this->pushNotificationProviderResponseBuilder->createPushNotificationProviderResponse(
                new ArrayObject([$pushNotificationProviderTransfers->getIterator()->current()]),
            );
        }

        $errorTransfer = (new ErrorTransfer())->setMessage(PushNotificationsBackendApiConfig::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND);

        return $this->errorResponseBuilder->createErrorResponse(
            new ArrayObject([$errorTransfer]),
            $glueRequestTransfer->getLocale(),
        );
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer|null
     */
    public function findPushNotificationProviderByUuid(string $uuid): ?PushNotificationProviderTransfer
    {
        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())->addUuid($uuid);

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        $pushNotificationProviderTransfers = $this->pushNotificationFacade
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer)
            ->getPushNotificationProviders();

        return $pushNotificationProviderTransfers->getIterator()->current();
    }
}
