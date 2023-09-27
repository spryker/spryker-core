<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Translator\PushNotificationTranslatorInterface;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponseBuilder implements ErrorResponseBuilderInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_LOCALE_NAME = 'en_US';

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig
     */
    protected PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig;

    /**
     * @var \Spryker\Glue\PushNotificationsBackendApi\Processor\Translator\PushNotificationTranslatorInterface
     */
    protected PushNotificationTranslatorInterface $pushNotificationTranslator;

    /**
     * @param \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig
     * @param \Spryker\Glue\PushNotificationsBackendApi\Processor\Translator\PushNotificationTranslatorInterface $pushNotificationTranslator
     */
    public function __construct(
        PushNotificationsBackendApiConfig $pushNotificationsBackendApiConfig,
        PushNotificationTranslatorInterface $pushNotificationTranslator
    ) {
        $this->pushNotificationsBackendApiConfig = $pushNotificationsBackendApiConfig;
        $this->pushNotificationTranslator = $pushNotificationTranslator;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createErrorResponse(
        ArrayObject $errorTransfers,
        ?string $localeName = null
    ): GlueResponseTransfer {
        $validationGlossaryKeyToRestErrorMapping = $this->pushNotificationsBackendApiConfig->getGlossaryKeyToErrorDataMapping();

        $translations = $this->pushNotificationTranslator->translateErrorTransferMessages(
            $errorTransfers,
            $localeName ?? static::DEFAULT_LOCALE_NAME,
        );

        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($errorTransfers as $errorTransfer) {
            $glueErrorTransfer = $this->createGlueErrorTransfer(
                $errorTransfer->getMessageOrFail(),
                $translations,
                $validationGlossaryKeyToRestErrorMapping,
            );

            $glueResponseTransfer->addError($glueErrorTransfer);
        }

        return $this->setGlueResponseHttpStatus($glueResponseTransfer);
    }

    /**
     * @param string $errorMessage
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createErrorResponseFromErrorMessage(string $errorMessage, ?string $localeName = null): GlueResponseTransfer
    {
        $errorTransfer = (new ErrorTransfer())->setMessage($errorMessage);

        return $this->createErrorResponse(
            new ArrayObject([$errorTransfer]),
            $localeName,
        );
    }

    /**
     * @param string $glossaryKey
     * @param array<string, string> $translations
     * @param array<string, array<string, mixed>> $validationGlossaryKeyToRestErrorMapping
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createGlueErrorTransfer(
        string $glossaryKey,
        array $translations,
        array $validationGlossaryKeyToRestErrorMapping
    ): GlueErrorTransfer {
        if (!isset($validationGlossaryKeyToRestErrorMapping[$glossaryKey])) {
            return $this->createUnknownGlueErrorTransfer($translations[$glossaryKey]);
        }

        return (new GlueErrorTransfer())
            ->fromArray($validationGlossaryKeyToRestErrorMapping[$glossaryKey])
            ->setMessage($translations[$glossaryKey]);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function setGlueResponseHttpStatus(GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        $glueErrorTransfers = $glueResponseTransfer->getErrors();

        if ($glueErrorTransfers->count() !== 1) {
            return $glueResponseTransfer->setHttpStatus(
                Response::HTTP_MULTI_STATUS,
            );
        }

        $glueErrorTransfer = $glueErrorTransfers->getIterator()->current();

        return $glueResponseTransfer->setHttpStatus(
            $glueErrorTransfer->getStatus(),
        );
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createUnknownGlueErrorTransfer(string $message): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setMessage($message)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(PushNotificationsBackendApiConfig::RESPONSE_CODE_UNKNOWN_ERROR);
    }
}
