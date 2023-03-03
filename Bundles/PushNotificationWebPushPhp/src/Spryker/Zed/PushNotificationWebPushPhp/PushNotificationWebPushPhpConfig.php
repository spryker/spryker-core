<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp;

use Spryker\Shared\PushNotificationWebPushPhp\PushNotificationWebPushPhpConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Exception\VAPIDException;

class PushNotificationWebPushPhpConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines web push php provider name.
     *
     * @api
     *
     * @var string
     */
    public const WEB_PUSH_PHP_PROVIDER_NAME = 'web-push-php';

    /**
     * @uses \Minishlink\WebPush\Encryption::MAX_COMPATIBILITY_PAYLOAD_LENGTH
     *
     * @var int
     */
    protected const PUSH_NOTIFICATION_PAYLOAD_LIMIT = 3052;

    /**
     * Specification:
     * - Returns VAPID public key.
     *
     * @api
     *
     * @throws \Spryker\Zed\PushNotificationWebPushPhp\Business\Exception\VAPIDException
     *
     * @return string
     */
    public function VAPIDPublicKey(): string
    {
        $vapidPublicKey = $this->get(
            PushNotificationWebPushPhpConstants::VAPID_PUBLIC_KEY,
            false,
        );
        if ($vapidPublicKey) {
            return $vapidPublicKey;
        }

        throw new VAPIDException(
            sprintf(
                'VAPID public key is not pre-configured, please set %s configuration value.',
                PushNotificationWebPushPhpConstants::VAPID_PUBLIC_KEY,
            ),
        );
    }

    /**
     * Specification:
     * - Returns VAPID private key.
     *
     * @api
     *
     * @throws \Spryker\Zed\PushNotificationWebPushPhp\Business\Exception\VAPIDException
     *
     * @return string
     */
    public function VAPIDPrivateKey(): string
    {
        $vapidPrivateKey = $this->get(
            PushNotificationWebPushPhpConstants::VAPID_PRIVATE_KEY,
            false,
        );
        if ($vapidPrivateKey) {
            return $vapidPrivateKey;
        }

        throw new VAPIDException(
            sprintf(
                'VAPID private key is not pre-configured, please set %s configuration value.',
                PushNotificationWebPushPhpConstants::VAPID_PRIVATE_KEY,
            ),
        );
    }

    /**
     * Specification:
     * - Returns VAPID subject.
     *
     * @api
     *
     * @throws \Spryker\Zed\PushNotificationWebPushPhp\Business\Exception\VAPIDException
     *
     * @return string
     */
    public function VAPIDSubject(): string
    {
        $vapidSubject = $this->get(
            PushNotificationWebPushPhpConstants::VAPID_SUBJECT,
            false,
        );
        if ($vapidSubject) {
            return $vapidSubject;
        }

        throw new VAPIDException(
            sprintf(
                'VAPID subject is not pre-configured, please set %s configuration value.',
                PushNotificationWebPushPhpConstants::VAPID_SUBJECT,
            ),
        );
    }

    /**
     * Specification:
     * - Returns the maximal length of `PushNotification.payload` in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getPushNotificationPayloadMaxLength(): int
    {
        return static::PUSH_NOTIFICATION_PAYLOAD_LIMIT;
    }

    /**
     * Specification:
     * - Returns VAPID authentication data.
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getVAPIDAuthCredentials(): array
    {
        return [
            'VAPID' => [
                'subject' => $this->VAPIDSubject(),
                'publicKey' => $this->VAPIDPublicKey(),
                'privateKey' => $this->VAPIDPrivateKey(),
            ],
        ];
    }
}
