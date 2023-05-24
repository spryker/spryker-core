<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Client\PushNotificationsBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Extractor\ErrorMessageExtractorInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ResponseCreator implements ResponseCreatorInterface
{
    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapperInterface
     */
    protected PushNotificationSubscriptionResourceMapperInterface $pushNotificationSubscriptionResourceMapper;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig
     */
    protected PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Dependency\Client\PushNotificationsBackendApiToGlossaryStorageClientInterface
     */
    protected PushNotificationsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Extractor\ErrorMessageExtractorInterface
     */
    protected ErrorMessageExtractorInterface $errorMessageExtractor;

    /**
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapperInterface $pushNotificationSubscriptionResourceMapper
     * @param \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig
     * @param \Spryker\Glue\PushNotificationsBackendApi\Dependency\Client\PushNotificationsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Extractor\ErrorMessageExtractorInterface $errorMessageExtractor
     */
    public function __construct(
        PushNotificationSubscriptionResourceMapperInterface $pushNotificationSubscriptionResourceMapper,
        PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig,
        PushNotificationsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient,
        ErrorMessageExtractorInterface $errorMessageExtractor
    ) {
        $this->pushNotificationSubscriptionResourceMapper = $pushNotificationSubscriptionResourceMapper;
        $this->pushNotificationsBackendApiConfig = $pushNotificationsBackendApiConfig;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->errorMessageExtractor = $errorMessageExtractor;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
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

        return $glueResponseTransfer->setHttpStatus(Response::HTTP_CREATED);
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationSubscriptionErrorResponse(
        ArrayObject $errorTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $glueResponseTransfer = (new GlueResponseTransfer())->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $validationGlossaryKeyToRestErrorMapping = $this->pushNotificationsBackendApiConfig->getValidationGlossaryKeyToRestErrorMapping();

        $errorMessages = $this->errorMessageExtractor->extractErrorMessages($errorTransfers);
        $errorMessageTranslations = $this->glossaryStorageClient->translateBulk(
            $errorMessages,
            $glueRequestTransfer->getLocaleOrFail(),
        );
        foreach ($errorTransfers as $errorTransfer) {
            $errorData = $validationGlossaryKeyToRestErrorMapping[$errorTransfer->getMessageOrFail()] ?? $this->getDefaultErrorData();
            $errorData[GlueErrorTransfer::MESSAGE] = $errorMessageTranslations[$errorTransfer->getMessageOrFail()];
            $glueErrorTransfer = $this->createGlueErrorTransfer($errorData);

            $glueResponseTransfer->addError($glueErrorTransfer);
        }

        return $glueResponseTransfer->setHttpStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createPushNotificationSubscriptionResourceTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): GlueResourceTransfer {
        $apiPushNotificationSubscriptionsAttributesTransfer = $this->pushNotificationSubscriptionResourceMapper
            ->mapPushNotificationSubscriptionTransferToApiPushNotificationSubscriptionsAttributesTransfer(
                $pushNotificationSubscriptionTransfer,
                new ApiPushNotificationSubscriptionsAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($pushNotificationSubscriptionTransfer->getUuidOrFail())
            ->setType(PushNotificationsBackendApiConfig::RESOURCE_PUSH_NOTIFICATION_SUBSCRIPTIONS)
            ->setAttributes($apiPushNotificationSubscriptionsAttributesTransfer);
    }

    /**
     * @param array<string, mixed> $errorData
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createGlueErrorTransfer(array $errorData): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())->fromArray($errorData);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultErrorData(): array
    {
        return [
            GlueErrorTransfer::CODE => PushNotificationsBackendApiConfig::RESPONSE_CODE_PUSH_NOTIFICATION_DEFAULT,
            GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
        ];
    }
}
