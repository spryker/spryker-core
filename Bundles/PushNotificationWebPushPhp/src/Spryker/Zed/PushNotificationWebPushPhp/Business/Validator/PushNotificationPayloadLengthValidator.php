<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface;
use Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig;

class PushNotificationPayloadLengthValidator implements PushNotificationPayloadLengthValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PAYLOAD_LENGTH_EXCEEDED = 'push_notification_web_push_php.validation.error.payload_length_exceeded';

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface
     */
    protected PushNotificationWebPushPhpToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface
     */
    protected ErrorCreatorInterface $errorCreator;

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig
     */
    protected PushNotificationWebPushPhpConfig $pushNotificationWebPushPhpConfig;

    /**
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface $errorCreator
     * @param \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig $pushNotificationWebPushPhpConfig
     */
    public function __construct(
        PushNotificationWebPushPhpToUtilEncodingServiceInterface $utilEncodingService,
        ErrorCreatorInterface $errorCreator,
        PushNotificationWebPushPhpConfig $pushNotificationWebPushPhpConfig
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->errorCreator = $errorCreator;
        $this->pushNotificationWebPushPhpConfig = $pushNotificationWebPushPhpConfig;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validatePayloadLength(
        ArrayObject $pushNotificationTransfers
    ): ErrorCollectionTransfer {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($pushNotificationTransfers as $i => $pushNotificationTransfer) {
            if (!$this->isApplicable($pushNotificationTransfer)) {
                continue;
            }
            if ($this->isValid($pushNotificationTransfer)) {
                continue;
            }
            $errorTransfer = $this->errorCreator->createErrorTransfer(
                (string)$i,
                static::GLOSSARY_KEY_VALIDATION_PAYLOAD_LENGTH_EXCEEDED,
            );
            $errorCollectionTransfer->addError($errorTransfer);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     *
     * @return bool
     */
    protected function isApplicable(PushNotificationTransfer $pushNotificationTransfer): bool
    {
        $pushNotificationProviderName = $pushNotificationTransfer->getProviderOrFail()->getNameOrFail();

        return $pushNotificationProviderName === PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     *
     * @return bool
     */
    protected function isValid(PushNotificationTransfer $pushNotificationTransfer): bool
    {
        $payloadEncoded = $this->utilEncodingService->encodeJson($pushNotificationTransfer->getPayload()) ?: '';

        return strlen($payloadEncoded) <= $this->pushNotificationWebPushPhpConfig->getPushNotificationPayloadMaxLength();
    }
}
