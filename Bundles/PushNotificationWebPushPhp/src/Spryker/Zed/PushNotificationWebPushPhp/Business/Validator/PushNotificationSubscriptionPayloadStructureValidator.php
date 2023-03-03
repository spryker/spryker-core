<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig;

class PushNotificationSubscriptionPayloadStructureValidator implements PushNotificationSubscriptionPayloadStructureValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_INVALID_PAYLOAD_STRUCTURE = 'push_notification_web_push_php.validation.error.invalid_payload_structure';

    /**
     * @var string
     */
    protected const PAYLOAD_KEY_ENDPOINT = 'endpoint';

    /**
     * @var string
     */
    protected const PAYLOAD_KEY_PUBLIC_KEY = 'publicKey';

    /**
     * @var string
     */
    protected const PAYLOAD_KEY_AUTH_TOKEN = 'authToken';

    /**
     * @var string
     */
    protected const PAYLOAD_KEY_KEYS = 'keys';

    /**
     * @var string
     */
    protected const PAYLOAD_KEYS_P256_DH = 'p256dh';

    /**
     * @var string
     */
    protected const PAYLOAD_KEYS_AUTH = 'auth';

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface
     */
    protected ErrorCreatorInterface $errorCreator;

    /**
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface $errorCreator
     */
    public function __construct(ErrorCreatorInterface $errorCreator)
    {
        $this->errorCreator = $errorCreator;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSubscriptions(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): ErrorCollectionTransfer {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($pushNotificationSubscriptionTransfers as $i => $pushNotificationSubscriptionTransfer) {
            if (!$this->isApplicable($pushNotificationSubscriptionTransfer)) {
                continue;
            }
            if ($this->isValidPayload($pushNotificationSubscriptionTransfer->getPayload())) {
                continue;
            }
            $errorTransfer = $this->errorCreator->createErrorTransfer(
                (string)$i,
                static::GLOSSARY_KEY_VALIDATION_INVALID_PAYLOAD_STRUCTURE,
            );
            $errorCollectionTransfer->addError($errorTransfer);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return bool
     */
    protected function isApplicable(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): bool {
        $pushNotificationSubscriptionProviderName = $pushNotificationSubscriptionTransfer->getProviderOrFail()->getNameOrFail();

        return $pushNotificationSubscriptionProviderName === PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME;
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return bool
     */
    protected function isValidPayload(array $payload): bool
    {
        if (empty($payload[static::PAYLOAD_KEY_ENDPOINT])) {
            return false;
        }
        if (count(array_keys($payload)) === 1) {
            return true;
        }
        if (!empty($payload[static::PAYLOAD_KEY_PUBLIC_KEY]) && !empty($payload[static::PAYLOAD_KEY_AUTH_TOKEN])) {
            return true;
        }

        return $this->isValidPayloadKeys($payload);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return bool
     */
    protected function isValidPayloadKeys(array $payload): bool
    {
        $keys = $payload[static::PAYLOAD_KEY_KEYS] ?? [];
        if ($keys === []) {
            return false;
        }

        if (!empty($keys[static::PAYLOAD_KEYS_P256_DH]) && !empty($keys[static::PAYLOAD_KEYS_AUTH])) {
            return true;
        }

        return false;
    }
}
