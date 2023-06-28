<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Deleter;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Reader\PushNotificationProviderReaderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationProviderDeleter implements PushNotificationProviderDeleterInterface
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
    public function deletePushNotificationProvider(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!$this->isRequestedEntityValid($glueRequestTransfer)) {
            return $this->createErrorResponse(
                $glueRequestTransfer,
                PushNotificationsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
            );
        }

        $pushNotificationProviderCollectionDeleteCriteriaTransfer = $this->createPushNotificationProviderCollectionDeleteCriteriaTransfer($glueRequestTransfer->getResourceOrFail());
        $pushNotificationProviderCollectionResponseTransfer = $this->pushNotificationFacade->deletePushNotificationProviderCollection($pushNotificationProviderCollectionDeleteCriteriaTransfer);
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
        return (bool)$glueRequestTransfer->getResourceOrFail()->getId();
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
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer
     */
    protected function createPushNotificationProviderCollectionDeleteCriteriaTransfer(
        GlueResourceTransfer $glueResourceTransfer
    ): PushNotificationProviderCollectionDeleteCriteriaTransfer {
        return (new PushNotificationProviderCollectionDeleteCriteriaTransfer())
            ->addUuid($glueResourceTransfer->getIdOrFail())
            ->setIsTransactional(true);
    }
}
