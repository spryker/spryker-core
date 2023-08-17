<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class PushNotificationsBackendApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines the push-notification-subscriptions resource name.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_PUSH_NOTIFICATION_SUBSCRIPTIONS = 'push-notification-subscriptions';

    /**
     * Specification:
     * - Defines the push-notification-providers resource name.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_PUSH_NOTIFICATION_PROVIDERS = 'push-notification-providers';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_UNKNOWN_ERROR = '5400';

    /**
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY = 'push_notification.validation.wrong_request_body';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionProviderExistsValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\UuidExistencePushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND
     *
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND = 'push_notification.validation.error.push_notification_provider_not_found';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND = '5001';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_WRONG_GROUP_NAME = '5002';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SUBSCRIPTION_ALREADY_EXISTS = '5003';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS = '5004';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_EXISTS = '5005';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE = '5006';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH = '5007';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS = '5008';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_LOCALE_NOT_FOUND = '5009';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionGroupNameAllowedValidatorRule::GLOSSARY_KEY_VALIDATION_WRONG_GROUP_NAME
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_GROUP_NAME = 'push_notification.validation.error.wrong_group_name';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionUniqueValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_ALREADY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SUBSCRIPTION_ALREADY_EXISTS = 'push_notification.validation.error.push_notification_already_exists';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionLocaleExistsValidatorRule::GLOSSARY_KEY_VALIDATION_LOCALE_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_LOCALE_NOT_FOUND = 'push_notification.validation.error.locale_not_found';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationSubscriptionExistsPushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS = 'push_notification.validation.push_notification_subscription_exists';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationExistsPushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_EXISTS = 'push_notification.validation.push_notification_exists';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\NameUniquenessPushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE = 'push_notification.validation.push_notification_provider_name_is_not_unique';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\NameLengthPushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH = 'push_notification.validation.push_notification_provider_name_wrong_length';

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\NameExistencePushNotificationProviderValidatorRule::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS = 'push_notification.validation.push_notification_provider_name_exists';

    /**
     * Specification:
     * - Returns a map of glossary keys to REST Error data.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getGlossaryKeyToErrorDataMapping(): array
    {
        return [
            static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_WRONG_GROUP_NAME => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_WRONG_GROUP_NAME,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_SUBSCRIPTION_ALREADY_EXISTS => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SUBSCRIPTION_ALREADY_EXISTS,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_LOCALE_NOT_FOUND => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_LOCALE_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_EXISTS => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_EXISTS,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NAME_IS_NOT_UNIQUE,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
        ];
    }
}
