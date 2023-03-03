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
     * @api
     *
     * @var int
     */
    public const RESPONSE_CODE_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND = 5001;

    /**
     * @api
     *
     * @var int
     */
    public const RESPONSE_CODE_WRONG_GROUP_NAME = 5002;

    /**
     * @api
     *
     * @var int
     */
    public const RESPONSE_CODE_SUBSCRIPTION_ALREADY_EXISTS = 5003;

    /**
     * @api
     *
     * @var int
     */
    public const RESPONSE_CODE_PUSH_NOTIFICATION_DEFAULT = 5100;

    /**
     * @uses \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionProviderExistsValidatorRule::GLOSSARY_KEY_VALIDATION_PROVIDER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PROVIDER_NOT_FOUND = 'push_notification.validation.error.push_notification_provider_not_found';

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
     * Specification:
     * - Returns a map of glossary keys to REST Error data.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getValidationGlossaryKeyToRestErrorMapping(): array
    {
        return [
            static::GLOSSARY_KEY_VALIDATION_PROVIDER_NOT_FOUND => [
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
        ];
    }
}
