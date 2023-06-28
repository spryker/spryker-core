<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationProviderCreator implements PushNotificationProviderCreatorInterface
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
     * @param \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface $pushNotificationProviderMapper
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface $pushNotificationProviderResponseBuilder
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface $errorResponseBuilder
     */
    public function __construct(
        PushNotificationsBackendApiToPushNotificationFacadeInterface $pushNotificationFacade,
        PushNotificationProviderMapperInterface $pushNotificationProviderMapper,
        PushNotificationProviderResponseBuilderInterface $pushNotificationProviderResponseBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder
    ) {
        $this->pushNotificationFacade = $pushNotificationFacade;
        $this->pushNotificationProviderMapper = $pushNotificationProviderMapper;
        $this->pushNotificationProviderResponseBuilder = $pushNotificationProviderResponseBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationProvider(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ApiPushNotificationProvidersAttributesTransfer|null $apiPushNotificationProvidersAttributesTransfer */
        $apiPushNotificationProvidersAttributesTransfer = $glueRequestTransfer->getResourceOrFail()->getAttributes();

        if (!$apiPushNotificationProvidersAttributesTransfer || !$apiPushNotificationProvidersAttributesTransfer->getName()) {
            $errorTransfer = (new ErrorTransfer())->setMessage(PushNotificationsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY);

            return $this->errorResponseBuilder->createErrorResponse(
                new ArrayObject([$errorTransfer]),
                $glueRequestTransfer->getLocale(),
            );
        }

        $pushNotificationProviderTransfer = $this->pushNotificationProviderMapper
            ->mapApiPushNotificationProvidersAttributesTransferToPushNotificationProviderTransfer(
                $apiPushNotificationProvidersAttributesTransfer,
                new PushNotificationProviderTransfer(),
            );

        $pushNotificationProviderCollectionRequestTransfer = $this->createPushNotificationProviderCollectionRequestTransfer($pushNotificationProviderTransfer);
        $pushNotificationProviderCollectionResponseTransfer = $this->pushNotificationFacade
            ->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);

        return $this->createGlueResponseTransfer(
            $pushNotificationProviderCollectionResponseTransfer,
            $glueRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function createGlueResponseTransfer(
        PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
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
