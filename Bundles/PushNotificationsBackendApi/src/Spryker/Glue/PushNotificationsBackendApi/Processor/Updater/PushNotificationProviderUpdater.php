<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Reader\PushNotificationProviderReaderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationProviderUpdater implements PushNotificationProviderUpdaterInterface
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
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface
     */
    protected PushNotificationProviderMapperInterface $pushNotificationProviderMapper;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Reader\PushNotificationProviderReaderInterface
     */
    protected PushNotificationProviderReaderInterface $pushNotificationProviderReader;

    /**
     * @param \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface $pushNotificationProviderMapper
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface $pushNotificationProviderResponseBuilder
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Reader\PushNotificationProviderReaderInterface $pushNotificationProviderReader
     */
    public function __construct(
        PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade,
        PushNotificationProviderMapperInterface $pushNotificationProviderMapper,
        PushNotificationProviderResponseBuilderInterface $pushNotificationProviderResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder,
        PushNotificationProviderReaderInterface $pushNotificationProviderReader
    ) {
        $this->pushNotificationFacade = $pushNotificationFacade;
        $this->pushNotificationProviderMapper = $pushNotificationProviderMapper;
        $this->pushNotificationProviderResponseBuilder = $pushNotificationProviderResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
        $this->pushNotificationProviderReader = $pushNotificationProviderReader;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updatePushNotificationProvider(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!$this->isRequestedEntityValid($glueRequestTransfer)) {
            return $this->createErrorResponse(
                $glueRequestTransfer,
                PushNotificationsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
            );
        }

        $glueResourceTransfer = $glueRequestTransfer->getResourceOrFail();

        /** @var \Generated\Shared\Transfer\PushNotificationProvidersBackendApiAttributesTransfer $pushNotificationProvidersBackendApiAttributesTransfer */
        $pushNotificationProvidersBackendApiAttributesTransfer = $glueResourceTransfer->getAttributesOrFail();
        $pushNotificationProviderTransfer = $this->pushNotificationProviderReader->findPushNotificationProviderByUuid($glueResourceTransfer->getIdOrFail());

        if (!$pushNotificationProviderTransfer) {
            return $this->createErrorResponse(
                $glueRequestTransfer,
                PushNotificationsBackendApiConfig::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND,
            );
        }

        $pushNotificationProviderTransfer = $this->pushNotificationProviderMapper->mapPushNotificationProvidersBackendApiAttributesTransferToPushNotificationProviderTransfer(
            $pushNotificationProvidersBackendApiAttributesTransfer,
            $pushNotificationProviderTransfer,
        );

        $pushNotificationProviderCollectionRequestTransfer = $this->createPushNotificationProviderCollectionRequestTransfer($pushNotificationProviderTransfer);
        $pushNotificationProviderCollectionResponseTransfer = $this->pushNotificationFacade->updatePushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        $errorTransfers = $pushNotificationProviderCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->errorResponseBuilder->createErrorResponse(
                $errorTransfers,
                $glueRequestTransfer->getLocale(),
            );
        }

        return $this->pushNotificationProviderResponseBuilder->createPushNotificationProviderResponse(
            $pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function isRequestedEntityValid(GlueRequestTransfer $glueRequestTransfer): bool
    {
        $glueResourceTransfer = $glueRequestTransfer->getResourceOrFail();

        return $glueResourceTransfer->getId()
            && $glueResourceTransfer->getAttributes()
            && array_filter($glueResourceTransfer->getAttributesOrFail()->modifiedToArray());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function createErrorResponse(
        GlueRequestTransfer $glueRequestTransfer,
        string $errorMessage
    ): GlueResponseTransfer {
        $errorTransfer = (new ErrorTransfer())->setMessage($errorMessage);

        return $this->errorResponseBuilder->createErrorResponse(
            new ArrayObject([$errorTransfer]),
            $glueRequestTransfer->getLocale(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer
     */
    protected function createPushNotificationProviderCollectionRequestTransfer(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderCollectionRequestTransfer {
        return (new PushNotificationProviderCollectionRequestTransfer())
            ->addPushNotificationProvider($pushNotificationProviderTransfer)
            ->setIsTransactional(true);
    }
}
